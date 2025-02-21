<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    // Afficher le formulaire de changement de mot de passe
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    // Traiter le changement de mot de passe
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mettre à jour le mot de passe
        // ... validation ...

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed' => true // <-- Activer le flag
        ]);

        return redirect()->route('gestionnaire.dashboard'); // <-- Redirection finale

        // Rediriger vers le tableau de bord du gestionnaire
        return redirect()->route('gestionnaire.dashboard')->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
