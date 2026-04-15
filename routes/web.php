<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*

|--------------------------------------------------------------------------
| REDIRECTIONS DE SÉCURITÉ (Anti-404)
|--------------------------------------------------------------------------
*/
// Ces lignes interceptent les vieilles adresses du cache de ton téléphone
Route::get('/login.html', function () { return view('login'); });
Route::get('/accueil.html', function () { return view('welcome'); });
Route::redirect('/home', '/');



/*

|--------------------------------------------------------------------------
| AUTHENTIFICATION (POST)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

/*

|--------------------------------------------------------------------------
| ROUTES DES PAGES (Vues Blade)
|--------------------------------------------------------------------------
*/
// La page d'accueil (Welcome)
Route::get('/', function () { 
    return view('welcome'); 
})->name('accueil');

// La page de login
Route::get('/login', function () { 
    return view('login'); 
})->name('login');

Route::get('/register', function () { return view('register'); });
Route::get('/profil', function () { return view('profil'); });
Route::get('/carte', function () { return view('carte'); });
Route::get('/signalement', function () { return view('signalement'); });
Route::get('/notifications', function () { return view('notifications'); });
Route::get('/calendrier', function () { return view('calendrier'); });
Route::get('/aide', function () { return view('aide'); });

// Admin
Route::get('/admin', function () {
    return view('admin');
})->name('admin.dashboard');

/*

|--------------------------------------------------------------------------
| API TEST MAP
|--------------------------------------------------------------------------
*/
Route::get('/reports/map', function () {
    return response()->json([
        ['id' => 1, 'latitude' => 14.7167, 'longitude' => -17.4677, 'title' => 'Point Test Dakar', 'status' => 'pending'],
        ['id' => 2, 'latitude' => 14.7480, 'longitude' => -17.4520, 'title' => 'Zone Propre', 'status' => 'cleaned']
    ]);
});
