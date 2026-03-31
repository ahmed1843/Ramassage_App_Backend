<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/signalement', function () {
    return view('signalement');
});
Route::get('/notifications', function () {
    return view('notifications');
});
Route::get('/profil', function () {
    return view('profil');
});
Route::get('/aide', function () {
    return view('aide');
});
Route::get('/calendrier', function () {
    return view('calendrier');
});
Route::get('/register', function () {
    return view('register');
});
Route::get('/carte', function () {
    return view('carte');
});


// ✅ Doit être Route::get (car ton JS fait un fetch GET par défaut)
Route::get('/reports/map', function () {
    return response()->json([
        [
            'id' => 1,
            'latitude' => 14.7167,
            'longitude' => -17.4677,
            'title' => 'Point Test Dakar',
            'status' => 'pending' // Devrait apparaître en rouge
        ],
        [
            'id' => 2,
            'latitude' => 14.7480,
            'longitude' => -17.4520,
            'title' => 'Zone Propre',
            'status' => 'cleaned' // Devrait apparaître en vert
        ]
    ]);
});








