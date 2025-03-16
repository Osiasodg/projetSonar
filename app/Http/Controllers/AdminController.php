<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Societe;
use App\Models\Signataire;
use App\Models\Modele;
use App\Models\Audit;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Bon;



class AdminController extends Controller
{
    // Gestion des gestionnaires
    public function gestionnaires() {
        $gestionnaires = User::where('role', 'gestionnaire')->get();
        return view('admin.gestionnaires', compact('gestionnaires'));
    }

    public function createGestionnaire(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'service' => 'required|string|max:255',
        ]);
    
        User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'service' => $request->service,
            'role' => 'gestionnaire',
            'password' => Hash::make('password123'), // Mot de passe par défaut
            'password_changed' => false, // L'utilisateur doit changer son mot de passe
        ]);
    
        //return back()->with('success', 'Gestionnaire ajouté avec succès');
        return redirect()->route('admin.gestionnaires')->with('success', 'Gestionnaire ajouté avec succès');
    }

    // public function updateGestionnaire(Request $request, $id) {
    //     $gestionnaire = User::findOrFail($id);
    //     $gestionnaire->update($request->all());
    //     return back()->with('success', 'Gestionnaire mis à jour');
    // }

    public function updateGestionnaire(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'service' => 'required|string|max:255',
        ]);
    
        $gestionnaire = User::findOrFail($id);
        $gestionnaire->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'service' => $request->service,
        ]);
    
        return back()->with('success', 'Gestionnaire mis à jour');
    }

    public function deleteGestionnaire($id) {
        User::findOrFail($id)->delete();
        //return back()->with('success', 'Le Gestionnaire à été supprimé!');
        return redirect()->route('admin.gestionnaires')->with('success', 'Le Gestionnaire a été supprimé!');
    }

    // public function resetPassword($id) {
    //     User::findOrFail($id)->update(['password' => Hash::make('password123')]);
    //     return back()->with('success', 'Mot de passe réinitialisé');
    // }


    public function resetPassword($id)
    {
        // Trouver le gestionnaire par son ID
        $gestionnaire = User::findOrFail($id);

        // Réinitialiser le mot de passe à une valeur par défaut (par exemple, "password123")
        $gestionnaire->update([
            'password' => Hash::make('password123'), // Mot de passe par défaut
            'password_changed' => false, // Marquer que le mot de passe doit être changé
        ]);

        /// Rediriger avec un message de succès contenant le nom et prénom
        return redirect()->route('admin.gestionnaires')->with(
            'success',
            "Le mot de passe de {$gestionnaire->prenom} {$gestionnaire->name} a été réinitialisé avec succès."
        );
    }

    // Gestion des sociétés
    public function societes() {
        $societes = Societe::all();
        return view('admin.societes', compact('societes'));
    }

    public function createSociete(Request $request) {
        Societe::create($request->all());
        //return back()->with('success', 'Société ajoutée');
        return redirect()->route('admin.societes')->with('success', 'Société ajoutée');
    }

    public function updateSociete(Request $request, $id) {
        Societe::findOrFail($id)->update($request->all());
        //return back()->with('success', 'Société mise à jour');
        return redirect()->route('admin.societes')->with('success', 'Société mise à jour');
    }

    public function deleteSociete($id) {
        Societe::findOrFail($id)->delete();
        //return back()->with('success', 'Société supprimée');
        return redirect()->route('admin.societes')->with('success', 'Société supprimée');
    }

    // Gestion des signataires
    public function signataires() {
        $signataires = Signataire::all();
        return view('admin.signataires', compact('signataires'));
    }

    // public function createSignataire(Request $request) {
    //     Signataire::create($request->all());
    //     return back()->with('success', 'Signataire ajouté');
    // }
    public function createSignataire(Request $request) {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'poste' => 'required|string|max:255', // Nouveau champ
        ]);
    
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
    public function audit(Request $request) {
        $audits = Audit::orderBy('created_at', 'desc')->get();
        $totalUsers = User::where('role', 'gestionnaire')->count();
        $bons = Bon::orderBy('created_at', 'desc')->get();


        // ✅ Nombre total de bons générés
        $totalBons = Bon::count();

        // ✅ Montant total des bons générés
        $montantTotal = Bon::sum('montant');

        // ✅ Nombre total de bons validés
        $bonsValides = Bon::where('utilise', true)->count();

        // ✅ Montant total des bons validés
        $montantValide = Bon::where('utilise', true)->sum('montant');

        // Récupération des dates du formulaire (si soumises)
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        // Vérification de l'ordre des dates
        if ($dateDebut && $dateFin && $dateDebut > $dateFin) {
            return redirect()->route('admin.audit')->with('error', 'La date de fin doit être postérieure à la date de début.');
        }

        // ✅ Nombre total de bons
        $nombreBons = Bon::count();


        // Récupération et filtrage des bons
        $query = Bon::orderBy('created_at', 'desc');

        if ($dateDebut && $dateFin) {
            $query->whereBetween('created_at', [$dateDebut, $dateFin]);
        } else {
            // Par défaut, afficher uniquement les 20 derniers bons
            $query->limit(15);
        }


        $bonsParUtilisateur = Bon::selectRaw('user_id, COUNT(*) as total_bons, SUM(montant) as total_montant')
        ->groupBy('user_id')
        ->with('user') // Charge les utilisateurs associés
        ->get();
        
        

        return view('admin.audit', compact(
            'audits', 
            'totalUsers', 
            'totalBons', 
            'montantTotal', 
            'bonsValides', 
            'montantValide', 
            'bonsParUtilisateur', 
            'dateDebut', 
            'dateFin', 
            'nombreBons',
            'bons'
        ));
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }


    // public function dashboard() {
    //     // Récupérer tous les gestionnaires
    //     $gestionnaires = User::where('role', 'gestionnaire')->get();
    
    //     // Récupérer toutes les sociétés
    //     $societes = Societe::all();

    //     // Récupérer tous les modèles
    //     $modeles = Modele::all();

    //     $signataires = Signataire::all(); // Récupérer les signataires

    //     // Récupérer tous les audits
    //     $audits = Audit::orderBy('created_at', 'desc')->get();

    //     // Passer les données à la vue
    //     return view('admin.dashboard', compact('gestionnaires', 'societes', 'modeles', 'signataires', 'audits'));
    //     //return view('admin.dashboard', compact('gestionnaires', 'societes', 'modeles', 'signataires', 'audits') + ['showNavbar' => true]);
    //     }

    
}

