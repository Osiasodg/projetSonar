<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon;
use App\Imports\BonsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class GestionnaireController extends Controller
{
    
    // Affiche le tableau de bord du gestionnaire
    public function index()
    {
        return view('gestionnaire.dashboard');
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
        'logo' => 'required|image|max:2048',
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

    // Enregistrer les données dans la base de données
    foreach ($data[0] as $row) {
        Bon::create([
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
    }

    // Récupérer tous les bons pour les afficher
    $bons = Bon::all();

    // Retourner à la vue avec les données et un message de succès
    return back()
        ->with('success', 'Fichier importé avec succès !')
        ->with('bons', $bons); // Passer les données à la vue
}


    //methode pour generer les fichier pdf
    public function generatePDFs(Request $request)
    {
        // Récupérer tous les bons
        $bons = Bon::all();

        // Générer un PDF pour chaque bon
        $pdfs = [];
        foreach ($bons as $bon) {
            // Générer le QR code
           // $qrCode = QrCode::size(100)->generate($bon->numero);
            // Générer le QR Code en format image base64
        $qrData = "BON D'ACHAT N°: {$bon->numero}\n";
        $qrData .= "Bénéficiaire: {$bon->beneficiaire}\n";
        $qrData .= "Montant: " . number_format($bon->montant, 0, ',', ' ') . " FCFA\n";
        $qrData .= "Validité: " . date('d/m/Y', strtotime($bon->date_validite)) . "\n";

        $qrCode = QrCode::format('png')->size(200)->generate($qrData);
        file_put_contents(storage_path('app/public/qrcode.png'), $qrCode);

        //$qrCode = base64_encode(QrCode::format('png')->size(200)->generate($bon->numero));
            

             // Récupérer le chemin du logo
            $logoPath = storage_path('app/public/' . $bon->logo_path);

            // Passer les données à la vue
            $pdf = PDF::loadView('bons.bon-pdf', [
            'bon' => $bon,
            'qrCode' => $qrCode,
            'logoPath' => $logoPath,
            'entite' => $bon->entite, // Entité sélectionnée
            'directeur' => 'Thomas ZONGO' // Remplacez par une valeur dynamique si nécessaire
            ]);

            $pdfs[] = $pdf;
        }

        // Télécharger les PDF
        return response()->streamDownload(function () use ($pdfs) {
            foreach ($pdfs as $pdf) {
                echo $pdf->output();
            }
        }, 'bons.pdf');
    }
}