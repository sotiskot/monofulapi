<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//voyages
Route::get('/voyages', [voyageController::class, 'getVoyages']);
Route::post('/voyages', [voyageController::class, 'newVoyage']);
Route::put('voyages/{voyage-id}', [voyageController::class, 'editVoyage']);

//expenses
Route::post('/vessels/{vessel-id}/vessel-opex', [expenseController::class, 'getExpense']);
Route::get('/vessels/{vessel-id}/financial-report', [expenseController::class, 'vesselExpense']);