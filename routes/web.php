<?php

use App\Http\Controllers\Auth\TeamAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\TeamPasswordResetController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminGroupController;
use App\Http\Controllers\Admin\AdminMatchController;
use App\Http\Controllers\Admin\AdminKnockoutController;
use Illuminate\Support\Facades\Route;

// Főoldal -> események listája
Route::get('/', fn() => redirect()->route('events.index'));

// ============================================================
// Nyilvános útvonalak
// ============================================================
Route::get('/esemenyek', [EventController::class, 'index'])->name('events.index');
Route::get('/esemenyek/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/esemenyek/{event}/nevezes', [EventController::class, 'registerForm'])->name('events.register.form');
Route::post('/esemenyek/{event}/nevezes', [EventController::class, 'register'])->name('events.register');
Route::post('/esemenyek/{event}/visszalep', [EventController::class, 'cancelRegistration'])->name('events.cancel');

// Csapat statisztikák (publikus)
Route::get('/csapatok', [TeamController::class, 'stats'])->name('teams.stats');

// ============================================================
// Csapat Auth
// ============================================================
Route::middleware('guest:team')->group(function () {
    Route::get('/regisztracio', [TeamAuthController::class, 'showRegister'])->name('team.register');
    Route::post('/regisztracio', [TeamAuthController::class, 'register']);
    Route::get('/bejelentkezes', [TeamAuthController::class, 'showLogin'])->name('team.login');
    Route::post('/bejelentkezes', [TeamAuthController::class, 'login']);
});

Route::post('/kijelentkezes', [TeamAuthController::class, 'logout'])->name('team.logout');

// Elfelejtett jelszó (vendég számára)
Route::middleware('guest:team')->group(function () {
    Route::get('/elfelejtett-jelszo', [TeamPasswordResetController::class, 'showForgotForm'])->name('team.password.forgot.form');
    Route::post('/elfelejtett-jelszo', [TeamPasswordResetController::class, 'sendResetLink'])->name('team.password.forgot');
    Route::get('/jelszo-visszaallitas/{token}', [TeamPasswordResetController::class, 'showResetForm'])->name('team.password.reset.form');
    Route::post('/jelszo-visszaallitas', [TeamPasswordResetController::class, 'resetPassword'])->name('team.password.reset');
});

// Csapat profil (bejelentkezett csapatoknak)
Route::middleware('team.auth')->group(function () {
    Route::get('/csapat/iranyito', [TeamController::class, 'dashboard'])->name('team.dashboard');
    Route::get('/csapat/profil', [TeamController::class, 'profile'])->name('team.profile');
    Route::post('/csapat/profil', [TeamController::class, 'updateProfile'])->name('team.profile.update');
    Route::get('/csapat/jelszo', [TeamController::class, 'showChangePassword'])->name('team.password.change.form');
    Route::post('/csapat/jelszo', [TeamController::class, 'changePassword'])->name('team.password.change');
});

// ============================================================
// Admin Auth
// ============================================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/bejelentkezes', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/bejelentkezes', [AdminAuthController::class, 'login']);
    });

    Route::post('/kijelentkezes', [AdminAuthController::class, 'logout'])->name('logout');

    // Admin védett útvonalak
    Route::middleware('admin')->group(function () {

        // Események kezelése
        Route::get('/esemenyek', [AdminEventController::class, 'index'])->name('events.index');
        Route::get('/esemenyek/letrehozas', [AdminEventController::class, 'create'])->name('events.create');
        Route::post('/esemenyek', [AdminEventController::class, 'store'])->name('events.store');
        Route::get('/esemenyek/{event}', [AdminEventController::class, 'show'])->name('events.show');
        Route::get('/esemenyek/{event}/szerkesztes', [AdminEventController::class, 'edit'])->name('events.edit');
        Route::put('/esemenyek/{event}', [AdminEventController::class, 'update'])->name('events.update');
        Route::post('/esemenyek/{event}/nevezes-zar', [AdminEventController::class, 'closeRegistration'])->name('events.close-registration');
        Route::delete('/esemenyek/{event}/nevezes/{registrationId}', [AdminEventController::class, 'removeRegistration'])->name('events.remove-registration');

        // Csoportok generálása
        Route::get('/esemenyek/{event}/csoportok/generalas', [AdminGroupController::class, 'generateForm'])->name('groups.generate.form');
        Route::post('/esemenyek/{event}/csoportok/generalas', [AdminGroupController::class, 'generate'])->name('groups.generate');
        Route::get('/esemenyek/{event}/csoportok/{group}', [AdminGroupController::class, 'show'])->name('groups.show');
        Route::post('/esemenyek/{event}/tovabbjutok', [AdminGroupController::class, 'advanceTeams'])->name('groups.advance');

        // Meccs eredmények
        Route::post('/esemenyek/{event}/meccsek/{gameMatch}/eredmeny', [AdminMatchController::class, 'updateResult'])->name('matches.result');

        // Knockout eredmények
        Route::post('/esemenyek/{event}/kieseses/{knockoutMatch}/eredmeny', [AdminKnockoutController::class, 'updateResult'])->name('knockout.result');
    });
});
