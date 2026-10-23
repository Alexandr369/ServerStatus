<?php

use Azuriom\Plugin\ServerStatus\Controllers\Admin\IncidentController;
use Azuriom\Plugin\ServerStatus\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the plugin's RouteServiceProvider within a
| group using the "web" and "admin-access" middleware groups.
|
*/

Route::middleware('can:serverstatus.manage')->group(function () {
    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');

    Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    Route::put('/incidents/{incident}', [IncidentController::class, 'update'])->name('incidents.update');
    Route::delete('/incidents/{incident}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
});
