<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Client\Pages\Register;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/register', Register::class)->name('filament.client.register');

Route::get('/filament-redirect', function () {
    return view('dashboardfilament');
})->middleware(['auth']);
