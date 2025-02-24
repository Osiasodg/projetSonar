<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon;
use App\Imports\BonsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class GestionnaireController extends Controller
{
    
    // Affiche le tableau de bord du gestionnaire
    public function index()
    {
        return view('gestionnaire.dashboard');
    }

    // Importe un fichier Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
            'logo' => 'required|image|max:2048',
            'entite' => 'required|string',
            'date_validite' => 'required|date',
            'recepteur' => 'required|string'
        ]);

        // Logique d'importation ici
        Excel::import(new BonsImport, $request->file('file'));

        return back()->with('success', 'Fichier importé avec succès !');
    }

    // Génère un PDF pour un bon spécifique
    public function generatePDF(Bon $bon)
    {
        $pdf = Pdf::loadView('pdf.bon', ['bon' => $bon]);
        return $pdf->download('bon-' . $bon->id . '.pdf');
    }
}