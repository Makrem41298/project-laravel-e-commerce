<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\roleController;

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


Route::get('produit',[ProduitController::class,'index']);
Route::post('produit',[ProduitController::class,'store']);
Route::put('produit/{id}',[ProduitController::class,'update']);
Route::delete('produit/{id}',[ProduitController::class,'destroy']);
Route::get('produit/{id}',[ProduitController::class,'show']);
