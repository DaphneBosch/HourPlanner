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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('hourplanner', [App\Http\Controllers\HourplannerController::class, 'index'])->name('hourplanner');
Route::post('store-hour', [\App\Http\Controllers\HourplannerController::class, 'store']);
Route::post('edit-hour', [\App\Http\Controllers\HourplannerController::class, 'edit']);
Route::post('delete-hour', [\App\Http\Controllers\HourplannerController::class, 'destroy']);
