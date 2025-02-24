<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Societe;
use App\Models\Signataire;
use App\Models\Modele;
use App\Models\Audit;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    // Gestion des gestionnaires
    public function gestionnaires() {
        $gestionnaires = User::where('role', 'gestionnaire')->get();
        return view('admin.gestionnaires', compact('gestionnaires'));
    }

    public function createGestionnaire(Request $request) {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users',
            'service' => 'required',
        ]);

        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'service' => $request->service,
            'role' => 'gestionnaire',
            'password' => Hash::make('password123') // Mot de passe par défaut
        ]);

        return back()->with('success', 'Gestionnaire ajouté avec succès');
    }

    public function updateGestionnaire(Request $request, $id) {
        $gestionnaire = User::findOrFail($id);
        $gestionnaire->update($request->all());
        return back()->with('success', 'Gestionnaire mis à jour');
    }

    public function deleteGestionnaire($id) {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Gestionnaire supprimé');
    }

    public function resetPassword($id) {
        User::findOrFail($id)->update(['password' => Hash::make('password123')]);
        return back()->with('success', 'Mot de passe réinitialisé');
    }

    // Gestion des sociétés
    public function societes() {
        $societes = Societe::all();
        return view('admin.societes', compact('societes'));
    }

    public function createSociete(Request $request) {
        Societe::create($request->all());
        return back()->with('success', 'Société ajoutée');
    }

    public function updateSociete(Request $request, $id) {
        Societe::findOrFail($id)->update($request->all());
        return back()->with('success', 'Société mise à jour');
    }

    public function deleteSociete($id) {
        Societe::findOrFail($id)->delete();
        return back()->with('success', 'Société supprimée');
    }

    // Gestion des signataires
    public function signataires() {
        $signataires = Signataire::all();
        return view('admin.signataires', compact('signataires'));
    }

    public function createSignataire(Request $request) {
        Signataire::create($request->all());
        return back()->with('success', 'Signataire ajouté');
    }

    public function updateSignataire(Request $request, $id) {
        Signataire::findOrFail($id)->update($request->all());
        return back()->with('success', 'Signataire mis à jour');
    }

    public function deleteSignataire($id) {
        Signataire::findOrFail($id)->delete();
        return back()->with('success', 'Signataire supprimé');
    }

    // Gestion des modèles
    public function modeles() {
        $modeles = Modele::all();
        return view('admin.modeles', compact('modeles'));
    }

    public function createModele(Request $request) {
        Modele::create($request->all());
        return back()->with('success', 'Modèle ajouté');
    }

    public function updateModele(Request $request, $id) {
        Modele::findOrFail($id)->update($request->all());
        return back()->with('success', 'Modèle mis à jour');
    }

    public function deleteModele($id) {
        Modele::findOrFail($id)->delete();
        return back()->with('success', 'Modèle supprimé');
    }

    // Journal d'audit
    public function audit() {
        $audits = Audit::orderBy('created_at', 'desc')->get();
        return view('admin.audit', compact('audits'));
    }

    
}

