<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon;
use App\Imports\BonsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Societe; 


class GestionnaireController extends Controller
{
    
    // Affiche le tableau de bord du gestionnaire
    public function index()
    {
        //return view('gestionnaire.dashboard');
        //return view('gestionnaire.dashboard', ['showNavbar' => true]); // Afficher la navbar
        $societes = Societe::all(); // Récupération des sociétés
        return view('gestionnaire.dashboard', [
            'showNavbar' => true,
            'societes' => $societes
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
        // Validation des données reçues
        $validated = $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
            'logo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'entite' => 'required|string',
            'date_validite' => 'required|date',
            'recepteur' => 'required|string',
            'telephone' => 'required|string'
        ]);

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
            ]);

            // Ajouter le bon importé au tableau
            $importedBons[] = $bon;
        }

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
        // Récupérer les IDs des bons envoyés via le formulaire
        $bonIds = $request->input('bon_ids', []);

        // Récupérer les bons correspondants depuis la base de données
        $bons = Bon::whereIn('id', $bonIds)->get();

        // Créer un tableau pour stocker les chemins des QR Codes
        $qrCodes = [];

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
        }

        // Récupérer le chemin du logo
    // dd($bon->logo_path);

        $logoPath = storage_path('app/public/' . $bon->logo_path); // Assurez-vous que le fichier existe

        // Passer les données à la vue et générer le PDF
        $pdf = PDF::loadView('bons.bon-pdf', [
            'bons' => $bons,
            'qrCodes' => $qrCodes, // On passe maintenant un tableau de QR Codes
            'logoPath' => $logoPath,
            'entite' => $bons->first()->entite ?? 'Entité Inconnue', // Remplace par la bonne valeur
            'directeur' => 'Thomas ZONGO' // Remplace par une valeur dynamique si nécessaire
        ]);

        // Télécharger le PDF unique contenant tous les bons
        return $pdf->download('bons-pdf.pdf');
    }

}