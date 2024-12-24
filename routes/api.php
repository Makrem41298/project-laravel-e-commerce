<?php

use App\Http\Controllers\CategorieController;
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


Route::post('login/employe',[\App\Http\Controllers\Authentication\AuthEmployeController::class,'login'])->name('login.employe');
Route::post('employ/forget-password',[\App\Http\Controllers\Authentication\AuthEmployeController::class,'forgotPassword'])->name('password.email');
Route::get('employ/reset-password/{token}', function (string $token) {
    return  response()->json(['token' => $token],400);
})->name('password.reset');
Route::post('employ/reset-password',[\App\Http\Controllers\Authentication\AuthEmployeController::class,'resetPassword'])->name('password.update');
use Illuminate\Support\Facades\Mail;

Route::get('/send-email', function () {
    Mail::raw('Ceci est un test', function ($message) {
        $message->to('makrem050@gmail.com')
            ->subject('Test Email');
    });

    return 'Email envoyé avec succès.';
});
