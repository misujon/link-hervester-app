<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/save-urls', [LinkController::class, 'saveLinks'])->name('saveUrls');
Route::get('/fetch-domains', [LinkController::class, 'fetchDomains'])->name('fetchDomains');
Route::get('/fetch-links/{domain}', [LinkController::class, 'fetchLinks'])->name('fetchLinks');
