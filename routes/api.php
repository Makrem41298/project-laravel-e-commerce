<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\AvisController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('categories',[CategorieController::class,'index']);
Route::post('categories',[CategorieController::class,'store']);
Route::put('categories/{id}',[CategorieController::class,'update']);
Route::delete('categories/{id}',[CategorieController::class,'destroy']);
Route::get('categories/{id}',[CategorieController::class,'show']);


Route::get('avis',[AvisController::class,'index']);
Route::post('avis',[AvisController::class,'store']);
Route::put('avis/{id}',[AvisController::class,'update']);
Route::delete('avis/{id}',[AvisController::class,'destroy']);
Route::get('avis/{id}',[AvisController::class,'show']);
