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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;




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

    // public function deleteSignataire($id) {
    //     Signataire::findOrFail($id)->delete();
    //     return back()->with('success', 'Signataire supprimé');
    // }

    public function deleteSignataire($id)
    {
        try {
            $signataire = Signataire::findOrFail($id);

            // Vérifie s'il est lié à des bons
            if ($signataire->bons()->exists()) {
                return back()->with('error', 'Impossible de supprimer ce signataire car il est utilisé dans des bons.');
            }

            $signataire->delete();

            return back()->with('success', 'Signataire supprimé avec succès.');
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du signataire : ' . $e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la suppression du signataire.');
        }
    }


    // Gestion des modèles
    public function modeles() {
        // Chemin du dossier des modèles
        $chemin = resource_path('views/bons/bonModeles');
    
        // Récupérer les fichiers .blade.php et extraire leur nom sans extension
        $fichiers = collect(File::files($chemin))
            ->map(function ($file) {
                $nomComplet = $file->getFilename(); // Ex: "bon-pdf.blade.php"
                return Str::before($nomComplet, '.blade.php'); // Extrait "bon-pdf"
            });
    
        // Vérifier si les fichiers existent en base et les ajouter si besoin
        foreach ($fichiers as $nomFichier) {
            Modele::updateOrInsert(
                ['nom' => $nomFichier], // Condition : Si "nom" existe, mettre à jour
                ['description' => DB::raw("IF(description IS NULL OR description = 'Aucune description', '$nomFichier', description)"), 'elements' => json_encode([])]
            );
            
            
            
        }
    
        // Récupérer les modèles en base
        $modeles = Modele::all();
    
        return view('admin.modeles', compact('modeles'));
    }

    //previsualisation du modele
    public function previewModele($modele) {
        $modelePath = "bons.$modele";
    
        if (!view()->exists($modelePath)) {
            return back()->with('error', 'Modèle introuvable.');
        }
    
        return view($modelePath, [
            'montant' => '10 000',
            'beneficiaire' => 'John Doe',
            'date' => now()->format('d/m/Y')
        ]);
    }


    public function updateDescription(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $modele = Modele::findOrFail($id);
        $modele->update(['description' => $request->description]);

        return response()->json(['success' => true, 'message' => 'Description mise à jour']);
    }

    
    
    
    public function createModele(Request $request) {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'elements' => 'required', // On attend ici la structure JSON
        ]);
    
        Modele::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null, // Stocker la description
            'elements' => $validated['elements'],
        ]);
    
        return back()->with('success', 'Modèle ajouté avec succès.');
    }
    
    
    // Mettre à jour uniquement la description du modèle
    public function updateModele(Request $request, $id) {
        $modele = Modele::findOrFail($id);

        $request->validate([
            'description' => 'nullable|string',
        ]);

        $modele->update(['description' => $request->description]);

        return response()->json(['success' => true, 'message' => 'Description mise à jour avec succès.']);
    }
    



    
    public function deleteModele($id) {
        Modele::findOrFail($id)->delete();
        return back()->with('success', 'Modèle supprimé.');
    }

    // Journal d'audit
    public function audit(Request $request) {
        // Récupération des audits
        $audits = Audit::orderBy('created_at', 'desc')->get();
    
        // Statistiques de base
        $totalUsers = User::where('role', 'gestionnaire')->count();
        $totalBons = Bon::where('is_generated', true)->count();
        $montantTotal = Bon::where('is_generated', true)->sum('montant');
    
        // Statistiques sur les bons validés
        $bonsValides = Bon::where('is_generated', true)->where('utilise', true)->count();
        $montantValide = Bon::where('is_generated', true)->where('utilise', true)->sum('montant');
    
        // Récupération des dates du formulaire
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
    
        // Validation des dates
        if ($dateDebut && $dateFin) {
            $startDate = Carbon::parse($dateDebut);
            $endDate = Carbon::parse($dateFin);
    
            if ($startDate->greaterThan($endDate)) {
                return redirect()->route('admin.audit')->with('error', 'L\'ordre des dates n\'est pas valide.');
            }
        }
    
        // Construction de la requête pour les bons
        $query = Bon::where('is_generated', true)->orderBy('created_at', 'desc');

    
        // Filtrage par dates si les dates sont fournies
        if ($dateDebut && $dateFin) {
            $query->whereBetween('created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ]);
        } else {
            // Limite par défaut à 15 résultats si aucun filtre n'est appliqué
            $query->limit(15);
        }
    
        // Récupération des bons filtrés
        $bons = $query->get();
        $nombreBons = $bons->count();
    
        // Statistiques par utilisateur
        $bonsParUtilisateur = Bon::where('is_generated', true)
            ->selectRaw('user_id, COUNT(*) as total_bons, SUM(montant) as total_montant')
            ->groupBy('user_id')
            ->with('user')
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
        // Nombre d'utilisateurs connectés (activité récente)
        User::where('last_activity', '>=', now()->subMinutes(5))->get(['name', 'prenom']);



        $utilisateursConnectes = [];
        
    // Nombre total de sociétés
    $totalSocietes = Societe::count();

    // Nombre total de bons cadeaux
    $totalBons = Bon::where('is_generated', true)->count();

    // Récupérer les 10 dernières activités des gestionnaires
    $activites = Audit::whereHas('user', function ($query) {
        $query->where('role', 'gestionnaire');
    })
    ->with('user')
    ->latest()
    ->take(10)
    ->get();

        // Passer les données à la vue
        return view('admin.dashboard', compact(
            'utilisateursConnectes',
            'totalSocietes',
            'totalBons',
            'activites'
        ));
        //return view('admin.dashboard');
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

