<?php

use Azuriom\Plugin\ServerStatus\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the plugin's RouteServiceProvider within a
| group using the "web" middleware group and the "serverstatus" prefix.
|
| Unlike most plugins, this page is public on purpose: a status page is
| meant to be checked by anyone, logged in or not, so no "auth" or "can"
| middleware is applied here.
|
*/

Route::get('/', [StatusController::class, 'index'])->name('index');
Route::get('/history', [StatusController::class, 'history'])->name('history');
Route::get('/badge/{server}', [StatusController::class, 'badge'])->name('badge');
