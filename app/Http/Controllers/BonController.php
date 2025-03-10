<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon;
use Carbon\Carbon;

class BonController extends Controller
{
    // Vérifie le bon sans le marquer comme utilisé
    public function verifier(Request $request)
    {
        $request->validate(['numero' => 'required|string']);

        $bon = Bon::where('numero', $request->numero)->first();

        if (!$bon) {
            return back()->with('error', 'Bon invalide'); //numéro introuvable
        }

        if (Carbon::parse($bon->date_validite)->isPast()) {
            return back()->with('error', 'Bon invalide (date expirée)'); //date expirée
        }

        if ($bon->utilise) {
            return back()->with('error', 'Bon déjà utilisé');
        }

        // Renvoie le bon valide sans le marquer comme utilisé
        return back()->with([
            'success' => 'Bon valide',
            'bon' => $bon // On passe le bon à la vue
        ]);
    }

    // Valide le bon (le marque comme utilisé)
    public function valider(Request $request)
    {
        $request->validate(['numero' => 'required|string']);

        $bon = Bon::where('numero', $request->numero)->first();

        if (!$bon) {
            return back()->with('error', 'Bon invalide (numéro introuvable)');
        }

        if (Carbon::parse($bon->date_validite)->isPast()) {
            return back()->with('error', 'Bon invalide (date expirée)');
        }

        if ($bon->utilise) {
            return back()->with('error', 'Bon déjà utilisé');
        }

        // Marque le bon comme utilisé
        $bon->update([
            'utilise' => true,
            'date_validation' => Carbon::now()
        ]);

        return back()->with('success', 'Bon validé avec succès');
    }

    // Affiche le formulaire
    public function showForm()
    {
        return view('bons.verifier');
    }
}