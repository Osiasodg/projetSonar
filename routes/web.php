<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionnaireController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\LoginController;
//use App\Http\Middleware\CheckRole;

// ----------------------------
// Routes publiques (sans authentification)
// ----------------------------

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home.form');

// Affichage du formulaire de connexion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Traitement de la connexion
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Affichage du formulaire d'inscription
Route::get('/register', function () {
    return view('auth.register'); // Redirige vers la vue d'inscription
})->name('register.form');

// Affichage du formulaire de vérification des bons
Route::get('/verifier', function () {
    return view('bons.verifier'); // Formulaire de vérification
})->name('verifier.form');

// Affichage du formulaire de vérification des bons (alternative)
// Afficher le formulaire
Route::get('/verifier-bon', [BonController::class, 'showForm'])->name('bons.showForm');
//Route::get('/bons/verifier', [BonController::class, 'showForm'])->name('bons.form');

// Vérifier le bon (sans le valider)
Route::post('/verifier-bon', [BonController::class, 'verifier'])->name('bons.verifier');

// Valider le bon (marquer comme utilisé)
Route::post('/valider-bon', [BonController::class, 'valider'])->name('bons.valider');

// Traitement de la vérification des bons
//Route::post('/verifier', [BonController::class, 'verifier'])->name('bons.verifier');

// ----------------------------
// Routes d'authentification (gestionnaires/admin)
// ----------------------------

// Désactive l'inscription par défaut
Auth::routes(['register' => false]);

// Redirection après connexion
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ----------------------------
// Routes protégées par authentification
// ----------------------------

Route::middleware(['auth'])->group(function () {
    // Afficher le formulaire de changement de mot de passe
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    
    // Traiter le changement de mot de passe
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('password.update');
});



// ----------------------------
// Routes pour les gestionnaires
// ----------------------------

// Redirection vers le tableau de bord du gestionnaire
Route::get('/gestionnaire/dashboard', [GestionnaireController::class, 'index'])->name('gestionnaire.dashboard');

//Route::prefix('gestionnaire')->middleware(['auth', 'force.password.change', 'role:gestionnaire'])->group(function () {
    // Tableau de bord des gestionnaires
 //  Route::get('/dashboard', [GestionnaireController::class, 'index'])->name('gestionnaire.dashboard');

    // Importation de fichiers (Excel, etc.)
 //   Route::post('/import', [BonController::class, 'import'])->name('import');
//});

   

// ----------------------------
// Routes pour les administrateurs
// ----------------------------

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Route::get('/gestionnaire', function () {
    //     return view('gestionnaire.dashboard');
    // })->name('gestionnaire.dashboard');


    // Importation de fichiers Excel
    //Route::post('/import', [GestionnaireController::class, 'import'])->name('gestionnaire.import');
    Route::middleware(['auth'])->prefix('gestionnaire')->group(function () {
        Route::post('/import', [GestionnaireController::class, 'import'])->name('gestionnaire.import');
    });
    
    // affichage des bons
    Route::get('/gestionnaire/dashboard', [GestionnaireController::class, 'index'])->name('gestionnaire.dashboard');


    // Génération de PDF
  //  Route::get('/generate-pdf/{bon}', [GestionnaireController::class, 'generatePDF'])->name('gestionnaire.generate-pdf');
    Route::post('/generate-pdfs', [GestionnaireController::class, 'generatePDFs'])->name('generate.pdfs');
    

    

// Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
//     // Tableau de bord des administrateurs
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('admin.dashboard');

    // Gestion des gestionnaires
    Route::get('/gestionnaires', [AdminController::class, 'gestionnaires'])->name('admin.gestionnaires');
    Route::post('/gestionnaires/create', [AdminController::class, 'createGestionnaire'])->name('admin.gestionnaires.create');
    Route::put('/gestionnaires/update/{id}', [AdminController::class, 'updateGestionnaire'])->name('admin.gestionnaires.update');
    //Route::post('/gestionnaires/delete/{id}', [AdminController::class, 'deleteGestionnaire'])->name('admin.gestionnaires.delete');
    Route::delete('/admin/gestionnaires/delete/{id}', [AdminController::class, 'deleteGestionnaire'])->name('admin.gestionnaires.delete');

   // Route::post('/gestionnaires/reset-password/{id}', [AdminController::class, 'resetPassword'])->name('admin.gestionnaires.reset');
    Route::put('/gestionnaires/reset-password/{id}', [AdminController::class, 'resetPassword'])->name('admin.gestionnaires.reset-password');

    // Gestion des sociétés
    Route::get('/societes', [AdminController::class, 'societes'])->name('admin.societes');
    Route::post('/societes/create', [AdminController::class, 'createSociete'])->name('admin.societes.create');
   // Route::post('/societes/update/{id}', [AdminController::class, 'updateSociete'])->name('admin.societes.update');
    Route::put('/societes/update/{id}', [AdminController::class, 'updateSociete'])->name('admin.societes.update');
    Route::delete('/societes/delete/{id}', [AdminController::class, 'deleteSociete'])->name('admin.societes.delete');


    // Gestion des signataires
    Route::get('/signataires', [AdminController::class, 'signataires'])->name('admin.signataires');
    Route::post('/signataires/create', [AdminController::class, 'createSignataire'])->name('admin.signataires.create');
    Route::put('/signataires/update/{id}', [AdminController::class, 'updateSignataire'])->name('admin.signataires.update');
    Route::delete('/signataires/delete/{id}', [AdminController::class, 'deleteSignataire'])->name('admin.signataires.delete');

    // Gestion des modèles
    Route::get('/modeles', [AdminController::class, 'modeles'])->name('admin.modeles');
    Route::post('/modeles/create', [AdminController::class, 'createModele'])->name('admin.modeles.create');
    Route::post('/modeles/update/{id}', [AdminController::class, 'updateModele'])->name('admin.modeles.update');
    Route::delete('/modeles/delete/{id}', [AdminController::class, 'deleteModele'])->name('admin.modeles.delete');
    Route::get('/modeles/edit/{id}', [AdminController::class, 'editModele'])->name('admin.modeles.edit');
   // Route::get('/admin/modeles/create', [AdminController::class, 'createModele'])->name('admin.modeles.create');
    Route::get('/modeles/create', [AdminController::class, 'showCreateModele'])->name('admin.modeles.create');
    Route::get('/admin/modeles/preview/{modele}', [AdminController::class, 'previewModele'])->name('admin.modeles.preview');
    Route::post('/modeles/update', [GestionnaireController::class, 'updateModele'])->name('admin.modeles.update');
    Route::put('/modeles/update/{id}', [ModeleController::class, 'update']);
    Route::put('/admin/modeles/update/{id}', [AdminController::class, 'updateDescription'])->name('admin.modeles.update');
    

    // Journal d'audit
    Route::get('/audit', [AdminController::class, 'audit'])->name('admin.audit');
    // Route pour vider la session dans gestionnaire/dashboard
    Route::post('/clear-session', [GestionnaireController::class, 'clearSession'])->name('clear.session');

    
//});