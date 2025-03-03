<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon;
use Carbon\Carbon;
use App\Imports\BonsAchatImport;

use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;


class BonController extends Controller
{
    public function verifier(Request $request)
    {
        $request->validate(['numero' => 'required|string']);

        $bon = Bon::where('numero', $request->numero)->first();

        if (!$bon) {
            return back()->with('error', 'Aucun bon trouvé avec ce numéro');
        }

        // Vérification de la date de validité
        if (Carbon::parse($bon->date_validite)->isPast()) {
            return back()->with('error', 'Bon invalide');
        }

        // Vérification de l'état d'utilisation
        if ($bon->utilise) {
            return back()->with('error', 'Bon invalide (déjà utilisé)');
        }

        // Mise à jour du bon comme "utilisé"
        $bon->update([
            'utilise' => true,
            'date_validation' => Carbon::now()
        ]);

        return back()->with('success', 'Bon validé avec succès');
    }

    public function showForm()
    {
        return view('bons.verifier'); // Charge la vue du formulaire
    }
}
