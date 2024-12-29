<?php

use App\Http\Controllers\Authentication\AuthEmployeController;
use App\Http\Controllers\Authentication\AuthUserController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EmployController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RoleController;
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
Route::get('categories/{id}',[CategorieController::class,'show']);

Route::group(['controller' => CategorieController::class,
    'prefix' => 'categories',
    'middleware' => ['verifiedToken:employs','permission:permissionsCategories']],function () {
    Route::post('/','store');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

//Rout authEmploy
Route::post('login/employe',[AuthEmployeController::class,'login'])->name('login.employe');
Route::post('employ/forget-password',[AuthEmployeController::class,'forgotPassword'])->name('password.email');
Route::get('employ/reset-password/{token}', function (string $token) {
    return  response()->json(['token' => $token],400);
})->name('password.reset');
Route::post('employ/reset-password',[AuthEmployeController::class,'resetPassword'])->name('password.update');
Route::post('employ/logout',[AuthEmployeController::class,'logout'])->name('employ.logout')->middleware('verifiedToken:employs');
Route::get('employ/employ-profile',[AuthEmployeController::class,'userProfile'])->name('employ.user.profile')->middleware('verifiedToken:employs');
//Rout user
Route::post('user/login',[AuthUserController::class,'login'])->name('login.employe');
Route::post('user/register',[AuthUserController::class,'register'])->name('login.employe');
Route::post('user/forget-password',[AuthUserController::class,'forgotPassword'])->name('password.email');
Route::post('user/reset-password',[AuthUserController::class,'resetPassword'])->name('password.update');
Route::get('user/logout',[AuthUserController::class,'logout'])->name('logout')->middleware('verifiedToken:users');
Route::get('user/user-profile',[AuthUserController::class,'userProfile'])->name('employ.user.profile')->middleware('verifiedToken:users');

//Rout Employ
Route::get('/employ/{id}', [EmployController::class, 'show'])->middleware('verifiedToken:employs');
Route::post('/employ', [EmployController::class, 'create'])->middleware('verifiedToken:employs');
Route::put('/employ/{id}', [EmployController::class, 'update'])->middleware('verifiedToken:employs');
Route::delete('/employ/{id}', [EmployController::class, 'destroy'])->middleware('verifiedToken:employs');
Route::get('/employ', [EmployController::class, 'index'])->middleware('verifiedToken:employs');

//Rout Produit

Route::get('/produits', [ProduitController::class, 'index']);
Route::post('/produits', [ProduitController::class, 'store'])->middleware('verifiedToken:employs');
Route::get('/produits/{id}', [ProduitController::class, 'show']);
Route::put('/produits/{id}', [ProduitController::class, 'update'])->middleware('verifiedToken:employs');
Route::delete('/produits/{id}', [ProduitController::class, 'destroy'])->middleware('verifiedToken:employs');

//Route commands

Route::get('/commandes', [CommandeController::class, 'index'])->middleware('verifiedToken');
Route::post('/commandes', [CommandeController::class, 'store'])->middleware('verifiedToken');
Route::put('/commandes/{id}/status', [CommandeController::class, 'updateStatusOrder'])->middleware('verifiedToken');
Route::get('/commandes/{id}', [CommandeController::class, 'show'])->middleware('verifiedToken');
Route::delete('/commandes/{id}', [CommandeController::class, 'delete'])->middleware('verifiedToken');

//Route avis
Route::prefix('avis')->group(function (){
    Route::get('/', [AvisController::class, 'index'])->name('avis.index');
    Route::post('/', [AvisController::class, 'store'])->name('avis.store');
    Route::get('/{id}', [AvisController::class, 'show'])->name('avis.show');
    Route::put('/{id}', [AvisController::class, 'update'])->name('avis.update');
    Route::delete('/{id}', [AvisController::class, 'destroy'])->name('avis.destroy');
})->middleware('verifiedToken:employs');


Route::get('roles', [RoleController::class, 'index'])->middleware('verifiedToken:employ');          // List all roles
Route::post('roles', [RoleController::class, 'store'])->middleware('verifiedToken:employ');         // Create a new role
Route::get('roles/{id}', [RoleController::class, 'show'])->middleware('verifiedToken:employ');      // Show a single role
Route::put('roles/{id}', [RoleController::class, 'update'])->middleware('verifiedToken:employ');    // Update a role
Route::delete('roles/{id}', [RoleController::class, 'destroy'])->middleware('verifiedToken:employ'); // Delete a role
