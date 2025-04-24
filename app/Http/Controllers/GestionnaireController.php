<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon;
use App\Imports\BonsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Societe;
use App\Models\Signataire; 
use App\Models\Audit;
use App\Models\Modele;
use Illuminate\Support\Facades\File;


class GestionnaireController extends Controller
{
    
    // Affiche le tableau de bord du gestionnaire
    public function index()
    {
        //return view('gestionnaire.dashboard');
        //return view('gestionnaire.dashboard', ['showNavbar' => true]); // Afficher la navbar
        $societes = Societe::all(); // Récupération des sociétés
        // Récupérer tous les signataires depuis la base de données
        $signataires = Signataire::all();

       // $modeles = \App\Models\Modele::all(); // Récupération de tous les modèles

         // Récupérer tous les modèles depuis la base de données
        $fichiersModeles = Modele::all(); 


        

        // Passer les données à la vue
        return view('gestionnaire.dashboard', [
            'showNavbar' => true,
            'societes' => $societes,
            'signataires' => $signataires,
            'fichiersModeles' => $fichiersModeles, // Ajout des modèles
        ]);
    }



    // Lecture du fichier Excel et retour pour previsualisation
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $data = Excel::toArray(new BonsImport, $file);

        // Retourner les données à la vue
        return view('gestionnaire.dashboard', ['previewData' => $data[0]]);
    }



    // Importe un fichier Excel
    public function import(Request $request)
    {
        //dd($request->all());
        // Validation des données reçues
        $validated = $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
            'logo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'entite' => 'required|string',
            'date_validite' => 'required|date',
            'recepteur' => 'required|string',
            'telephone' => 'required|string',
            'signataire_id' => 'required|exists:signataires,id',
            'modele_nom' => 'required|string|exists:modeles,nom',   
        ]);

         // Récupérer l'ID du modèle sélectionné
        $modele = Modele::where('nom', $validated['modele_nom'])->firstOrFail();

        // Enregistrer le logo et récupérer son chemin
        $logoPath = $request->file('logo')->store('logos', 'public');

        // Lire le fichier Excel et récupérer les données
        $data = Excel::toArray(new BonsImport(
            $validated['entite'], 
            $validated['date_validite'], 
            $validated['recepteur'], 
            $validated['telephone'], 
            $logoPath
        ), $request->file('file'));

        // Initialiser le tableau des bons importés
        $importedBons = [];

        // Enregistrer les données dans la base de données
        foreach ($data[0] as $row) {
            $bon = Bon::create([
                'numero' => date('Y') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT) . 'S', // Générer le numéro de bon
                'beneficiaire' => strtoupper($row['nom']) . ' ' . ucfirst(strtolower($row['prenom'])), 
                'montant' => (float) str_replace(',', '.', $row['montant']),
                'date_validite' => $validated['date_validite'],
                'utilise' => false,
                'recepteur' => $validated['recepteur'],
                'telephone' => $validated['telephone'],
                'entite' => $validated['entite'],
                'logo_path' => $logoPath,
                'user_id' => auth()->id() ?? 1, // Ajoute l'ID de l'utilisateur connecté, ou une valeur par défaut
                'signataire_id' => $validated['signataire_id'] ,// Enregistre l'ID du signataire
                'modele_id' => $modele->id,
                'is_generated' => false,

                
            ]);

            // Ajouter le bon importé au tableau
            $importedBons[] = $bon;
        }


        // Enregistrer l'action dans la table audits
        //dd(auth()->id());
       
        Audit::create([
            'user_id' => auth()->id(), // ID de l'utilisateur connecté (gestionnaire)
            'action' => 'Importation de bons', // Description de l'action
            'details' => 'Importation de ' . count($importedBons) . ' bons', // Détails supplémentaires
        ]);


        // Stocker les bons importés dans la session
        session(['importedBons' => $importedBons]);

        // Debug : Vérifier les données dans la session
    //dd(session('importedBons'));

        // Retourner à la vue avec les données et un message de succès
        return back()
            ->with('success', 'Fichier importé avec succès !')
            ->with('bons', $importedBons); // Passer uniquement les bons importés à la vue
    }


    //methode pour generer les fichier pdf
    public function generatePDFs(Request $request)
    {
        // Récupérer l'ID du modèle choisi
        //$modeleId = $request->input('modele_id');
        // Récupérer le modèle si sélectionné
        // $modele = null;
        // if ($modeleId) {
        //     $modele->elements = json_decode($modele->elements, true);
        // }



        // Récupérer les IDs des bons envoyés via le formulaire
        $bonIds = $request->input('bon_ids', []);

        // Récupérer les bons correspondants depuis la base de données
       // $bons = Bon::whereIn('id', $bonIds)->get();

        // Récupérer les bons avec leur modèle associé
         $bons = Bon::with('modele')->whereIn('id', $bonIds)->get();

        // Vérifier que tous les bons ont un modèle
        foreach ($bons as $bon) {
            if (!$bon->modele) {
                return back()->with('error', 'Un ou plusieurs bons n’ont pas de modèle associé.');
            }
        }

        $viewPath = "bons.bonModeles." . $bons->first()->modele->nom;

        // Créer un tableau pour stocker les chemins des QR Codes
        $qrCodes = [];

        // Créer un tableau pour stocker les informations des signataires
         $signataires = [];

        // Générer le QR Code pour chaque bon
        foreach ($bons as $bon) {
            $qrData = "BON D'ACHAT N°: {$bon->numero}\n";
            $qrData .= "Bénéficiaire: {$bon->beneficiaire}\n";
            $qrData .= "Montant: " . number_format($bon->montant, 0, ',', ' ') . " FCFA\n";
            $qrData .= "Validité: " . date('d/m/Y', strtotime($bon->date_validite)) . "\n";

            // Chemin unique pour chaque QR Code
            $qrPath = storage_path("app/public/qrcode_{$bon->id}.png");
            QrCode::format('png')->size(100)->generate($qrData, $qrPath);

            // Stocker le chemin du QR Code
            $qrCodes[$bon->id] = $qrPath;

            // Récupérer les informations du signataire
             $signataires[$bon->id] = $bon->signataire; // Utilise la relation définie dans le modèle Bon
        }


        // ✅ Marquer les bons comme générés
        foreach ($bons as $bon) {
            $bon->is_generated = true;
            $bon->save();
        }

        // Récupérer le chemin du logo
    // dd($bon->logo_path);

        $logoPath = storage_path('app/public/' . $bon->logo_path); // Assurez-vous que le fichier existe

        // Passer les données à la vue et générer le PDF
        $pdf = PDF::loadView($viewPath, [
            'bons' => $bons,
            'qrCodes' => $qrCodes, // On passe maintenant un tableau de QR Codes
            'logoPath' => $logoPath,
            'entite' => $bons->first()->entite ?? 'Entité Inconnue', // Remplace par la bonne valeur
            'directeur' => 'Thomas ZONGO', // Remplace par une valeur dynamique si nécessaire
            'signataires' => $signataires ,// Passer les informations des signataires
            'modele' => $bons->first()->modele, // Passage du modèle sélectionné
        ]);

        // Télécharger le PDF unique contenant tous les bons
        return $pdf->download("bons-{$bons->first()->modele->nom}.pdf");
    }

    public function signataire()
    {
        return $this->belongsTo(Signataire::class, 'signataire_id');
    }

    //methode pour vider la session et actualiser la page gestionnaire/dashboard.blade
    public function clearSession(Request $request)
    {
        // Vider les données de session
        $request->session()->forget(['importedBons', 'bons']);
       // dd($modeles);

        // Rediriger vers le tableau de bord
        return redirect()->route('gestionnaire.dashboard');
    }

    public function previewBon(Request $request)
    {
        \Log::info('Début previewBon', ['bon_ids' => $request->bon_ids]);

        try {
            $bonIds = $request->input('bon_ids', []);

            if (empty($bonIds)) {
                return response('<div class="alert alert-danger">Aucun bon sélectionné</div>', 400);
            }

            $bons = Bon::with(['modele', 'signataire'])->whereIn('id', $bonIds)->get();

            if ($bons->isEmpty()) {
                return response('<div class="alert alert-danger">Aucun bon trouvé</div>', 404);
            }

            $viewPath = "bons.bonModeles." . $bons->first()->modele->nom;
            if (!view()->exists($viewPath)) {
                return response('<div class="alert alert-danger">Vue modèle introuvable</div>', 404);
            }

            // Générer QR code (base64, pas fichiers)
            $qrCodes = [];
            foreach ($bons as $bon) {
                $qrData = "BON D'ACHAT N°: {$bon->numero}\nBénéficiaire: {$bon->beneficiaire}\nMontant: " .
                    number_format($bon->montant, 0, ',', ' ') . " FCFA\nValidité: " .
                    date('d/m/Y', strtotime($bon->date_validite)) . "\n";

                $qrCodes[$bon->id] = 'data:image/png;base64,' . base64_encode(
                    \QrCode::format('png')->size(100)->generate($qrData)
                );
            }

            // Logo en URL publique
            $logoPath = asset("storage/" . $bons->first()->logo_path);

            $html = view($viewPath, [
                'bons' => collect([$bons->first()]), // Juste 1 bon pour prévisualisation
                'qrCodes' => $qrCodes,
                'logoPath' => $logoPath,
                'entite' => $bons->first()->entite,
                'signataires' => [$bons->first()->id => $bons->first()->signataire],
                'modele' => $bons->first()->modele,
                'isPreview' => true
            ])->render();

            return response($html); // retour simple HTML

        } catch (\Exception $e) {
            \Log::error('Erreur previewBon: ' . $e->getMessage());
            return response('<div class="alert alert-danger">Erreur interne: ' . e($e->getMessage()) . '</div>', 500);
        }
    }


}