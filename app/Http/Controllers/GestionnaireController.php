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

        // Importer le fichier Excel
        Excel::import(new BonsImport(
            $validated['entite'], 
            $validated['date_validite'], 
            $validated['recepteur'], 
            $validated['telephone'], 
            $logoPath
        ), $request->file('file'));

        return back()->with('success', 'Fichier importé avec succès !');
    }
}