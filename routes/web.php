<?php

use Illuminate\Support\Facades\Route;

// 1. LES ROUTES ADMIN (Libres pour que ton JS puisse charger la page)
Route::get('/admin', function () {
    return view('admin'); // C'est ici que ton tableau vert s'affiche
})->name('admin.dashboard');

// 2. LES ROUTES CLIENTS
Route::get('/', function () { return view('welcome'); });

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

// 3. API TEST MAP
Route::get('/reports/map', function () {
    return response()->json([
        ['id' => 1, 'latitude' => 14.7167, 'longitude' => -17.4677, 'title' => 'Point Test Dakar', 'status' => 'pending'],
        ['id' => 2, 'latitude' => 14.7480, 'longitude' => -17.4520, 'title' => 'Zone Propre', 'status' => 'cleaned']
    ]);
});
