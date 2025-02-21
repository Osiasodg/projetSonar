<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon;

class BonController extends Controller
{
    public function verifier(Request $request)
    {
        $request->validate(['numero' => 'required|string']);

        $bon = Bon::where('numero', $request->numero)->first();

        if (!$bon) {
            return back()->with('error', 'Aucun bon trouvé avec ce numéro');
        }

        // Logique de vérification...
        
        return back()->with('success', 'Bon valide !');
    }

    public function showForm()
    {
        return view('bons.verifier'); // Charge la vue du formulaire
    }
}
