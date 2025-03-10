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
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed' => true,
        ]);

        //return redirect()->route('gestionnaire.dashboard')->with('success', 'Mot de passe mis à jour avec succès.');
        return redirect()->route('password.change')->with('success', true);

    }
}