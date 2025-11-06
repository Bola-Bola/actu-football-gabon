<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Models\inscription;
use App\Models\Interview;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\ResultatMatchController;
use App\Http\Controllers\ClassementController;
use App\Http\Controllers\ActualiteController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\AdminInterviewController;


Route::get('/a-propos', [AboutController::class, 'index'])->name('about');



Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/equipe', [HomeController::class, 'equipe'])->name('equipe');
Route::get('/match', [HomeController::class, 'match'])->name('match');
Route::get('/interviews', [HomeController::class, 'interviews'])->name('interviews');



// Page d'accueil → redirige vers login
Route::get('/login', function () {
    return redirect()->route('login');
});

// Routes de connexion
Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('login', [AdminController::class, 'login'])->name('login.post');

// Routes d'inscription
Route::get('inscription', [AdminController::class, 'showRegisterForm'])->name('inscription');
Route::post('inscription', [AdminController::class, 'register'])->name('register.post');

/*
|--------------------------------------------------------------------------
| Routes Protégées (Authentification requise)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')->group(function () {

    // Déconnexion
    Route::post('deconnexion', [AdminController::class, 'logout'])->name('deconnexion');

    // Dashboard principal
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Profil personnel (accessible à tous les admins connectés)
    Route::get('admin/profile/{id}', [AdminController::class, 'show'])->name('admin.profile');
    Route::get('admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');

    // Liste des administrateurs
    Route::get('admin/users', [AdminController::class, 'listAdmins'])->name('admin.users');

    // Paramètres
    Route::get('admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

    // Gestion avancée des admins (réservé aux super admins - vérification dans le contrôleur)
    Route::delete('admin/delete/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
    Route::post('admin/change-role/{id}', [AdminController::class, 'changeRole'])->name('admin.changeRole');
});





// Routes CRUD pour les équipes
Route::get('equipes', [EquipeController::class, 'index'])->name('equipes.index');
Route::get('equipes/create', [EquipeController::class, 'create'])->name('equipes.create');
Route::post('equipes', [EquipeController::class, 'store'])->name('equipes.store');
Route::get('equipes/{equipe}', [EquipeController::class, 'show'])->name('equipes.show');
Route::get('equipes/{equipe}/edit', [EquipeController::class, 'edit'])->name('equipes.edit');
Route::put('equipes/{equipe}', [EquipeController::class, 'update'])->name('equipes.update');
Route::delete('equipes/{equipe}', [EquipeController::class, 'destroy'])->name('equipes.destroy');
// Route pour les statistiques (API AJAX)
Route::get('equipes-stats', [EquipeController::class, 'getStats'])->name('equipes.stats');



// Routes CRUD pour les matchs
Route::get('matchs', [ResultatMatchController::class, 'index'])->name('matchs.index');
Route::get('matchs/create', [ResultatMatchController::class, 'create'])->name('matchs.create');
Route::post('matchs', [ResultatMatchController::class, 'store'])->name('matchs.store');
Route::get('matchs/statistiques', [ResultatMatchController::class, 'statistiques'])->name('matchs.statistiques');
Route::get('matchs/classement', [ResultatMatchController::class, 'classement'])->name('matchs.classement');
Route::get('matchs/{match}', [ResultatMatchController::class, 'show'])->name('matchs.show');
Route::get('matchs/{match}/edit', [ResultatMatchController::class, 'edit'])->name('matchs.edit');
Route::put('matchs/{match}', [ResultatMatchController::class, 'update'])->name('matchs.update');
Route::delete('matchs/{match}', [ResultatMatchController::class, 'destroy'])->name('matchs.destroy');
Route::resource('matchs', ResultatMatchController::class);
Route::get('/matchs/all', [ResultatMatchController::class, 'getAll'])->name('matchs.all');
Route::get('/matchs/stats', [ResultatMatchController::class, 'getStats'])->name('matchs.stats');




// Routes pour le classement
Route::get('/classements', [ClassementController::class, 'index'])->name('matchs');
Route::post('/classements/recalculer', [ClassementController::class, 'recalculer'])->name('classements.recalculer');



// Routes CRUD pour les actualité
Route::resource('actualites', ActualiteController::class);
Route::get('actualites', [ActualiteController::class, 'index'])->name('actualites.index');
Route::get('actualites/create', [ActualiteController::class, 'create'])->name('actualites.create');
Route::post('actualites', [ActualiteController::class, 'store'])->name('actualites.store');
Route::get('actualites/{actualite}', [ActualiteController::class, 'show'])->name('actualites.show');
Route::get('actualites/{actualite}/edit', [ActualiteController::class, 'edit'])->name('actualites.edit');
Route::put('actualites/{actualite}', [ActualiteController::class, 'update'])->name('actualites.update');
Route::delete('actualites/{actualite}', [ActualiteController::class, 'destroy'])->name('actualites.destroy');


// Route de test pour vérifier le classement
Route::get('/test-classement', function() {
    $saisonActive = \App\Models\Saison::where('active', true)->first()
        ?? \App\Models\Saison::first();

    $competition = \App\Models\Competition::where('type', 'championnat')->first()
        ?? \App\Models\Competition::first();

    if (!$saisonActive || !$competition) {
        return response()->json([
            'error' => 'Pas de saison ou compétition',
            'saison' => $saisonActive,
            'competition' => $competition
        ]);
    }

    $classement = \App\Models\Classement::where('saison_id', $saisonActive->id)
        ->where('competition_id', $competition->id)
        ->with('equipe')
        ->orderBy('points', 'desc')
        ->get();

    $matchs = \App\Models\ResultatMatch::where('saison_id', $saisonActive->id)
        ->where('competition_id', $competition->id)
        ->where('type_match', 'officiel')
        ->where('statut', 'termine')
        ->get();

    return response()->json([
        'saison' => $saisonActive->nom,
        'competition' => $competition->nom,
        'nombre_matchs_officiels' => $matchs->count(),
        'nombre_equipes_classees' => $classement->count(),
        'classement' => $classement->map(function($c) {
            return [
                'position' => $c->position,
                'equipe' => $c->equipe->nom,
                'points' => $c->points,
                'mj' => $c->matches_joues,
                'db' => $c->difference_buts
            ];
        })
    ]);
})->name('test.classement');




// Routes publiques





// Page principale (formulaire + tableau)
Route::get('interview', [InterviewController::class, 'index'])->name('interview.index');

// Création d'une interview
Route::post('interview', [InterviewController::class, 'store'])->name('interview.store');

// API : liste filtrée
Route::get('interview/all', [InterviewController::class, 'all'])->name('interview.all');

// API : statistiques globales
Route::get('interview/stats', [InterviewController::class, 'stats'])->name('interview.stats');

// Édition
Route::get('interview/{id}/edit', [InterviewController::class, 'edit'])->name('interview.edit');

// Mise à jour
Route::put('interview/{id}', [InterviewController::class, 'update'])->name('interview.update');

// Suppression
Route::delete('interview/{id}', [InterviewController::class, 'destroy'])->name('interview.destroy');

// Détails d'une interview (doit toujours être en dernier)
Route::get('interview/{id}', [InterviewController::class, 'show'])->name('interview.show');
