<?php

use App\Http\Controllers\Authentication\AuthEmployeController;
use App\Http\Controllers\Authentication\AuthUserController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\CategorieController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduitController;


use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EmployController;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

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
Route::group(['controller' => ProduitController::class,
    'prefix' => 'produit'], function () {
    Route::group(['middleware' => ['verifiedToken:employs','permission:permissionsProduits']],function (){
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
});
//Rout authEmploy
Route::group(['controller' => AuthEmployeController::class,
    'prefix' => 'employe'], function () {
    Route::post('/login','login')->name('login.employe');
    Route::post('/forget-password','forgotPassword')->name('password.email');
    Route::get('/reset-password/{token}', function (string $token) {
        return  response()->json(['token' => $token],400);
    })->name('password.reset');
    Route::post('/reset-password','resetPassword')->name('password.update');
    Route::post('/logout','logout')->name('employ.logout')->middleware('verifiedToken:employs');
    Route::get('/employ-profile','userProfile')->name('employ.user.profile')->middleware('verifiedToken:employs');
});


//Rout user
Route::group(['controller' => AuthUserController::class,
    'prefix' => 'user'], function () {
    Route::post('/login',  'login')->name('login.employe');
    Route::post('/register', 'register')->name('login.employe');
    Route::post('/forget-password', 'forgotPassword')->name('password.email');
    Route::post('/reset-password','resetPassword')->name('password.update');
    Route::get('/logout' ,'logout')->name('logout')->middleware('verifiedToken:users');
    Route::get('/user-profile', 'userProfile')->name('employ.user.profile')->middleware('verifiedToken:users');
});
//Rout Employ
Route::group(['controller' => EmployController::class,
    'prefix' => 'employ',
    'middleware' => ['verifiedToken:employs','permission:permissionsEmployes']],function () {
    Route::get('/{id}', 'show');
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::get('/', 'index');
});
//Rout Produit

Route::get('/produits', [ProduitController::class, 'index']);
Route::get('/produits/{id}', [ProduitController::class, 'show']);

Route::group(['controller' => ProduitController::class,
    'prefix' => 'produits',
    'middleware' => ['verifiedToken:employs','permission:permissionsProduits']],function () {
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
//Route commands
Route::group(['controller' => CommandeController::class,
    'prefix' => 'commandes',
    'middleware' => ['verifiedToken:users,employs'] ],function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::put('/{id}/status','updateStatusOrder');
    Route::get('/{id}', 'show');
    Route::delete('/{id}', 'delete');

});

//Route avis
Route::prefix('avis')->group(function (){
    Route::get('/', [AvisController::class, 'index'])->name('avis.index');
    Route::get('/{id}', [AvisController::class, 'show'])->name('avis.show');
    Route::delete('/{id}', [AvisController::class, 'destroy'])->middleware('verifiedToken:employs,users')->name('avis.destroy');
    Route::post('/', [AvisController::class, 'store'])->name('avis.store')->middleware('verifiedToken:users');
    Route::put('/{id}', [AvisController::class, 'update'])->name('avis.update')->middleware('verifiedToken:users');
});

Route::group(['controller' => RoleController::class,
    'prefix' => 'roles',
    'middleware' => ['verifiedToken:employs','permission:permissionsRoles']
    ],function () {
    Route::get('/',  'index');
    Route::post('/',  'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}', 'destroy');

});
//Route User
Route::apiResource('users', UserController::class)->middleware(['verifiedToken:employs','permission:permissionsUser'])->except('store');

Route::get('/permissions',function (){
    try {
        $permissions=Permission::all();

    }catch (\Exception $e){
        return  response()->json(['error' => $e->getMessage()],400);
    }
   return response()->json(['status' => true,'date'=>$permissions]);
})->middleware('verifiedToken:employs,permission:permissionsRole');
Route::middleware('verifiedToken:employs,role:Super_Admin')->group(function (){
    Route::get('/dashboard', [DashboardController::class, 'Super_Admin']);
    Route::get('/reviews', [DashboardController::class, 'totleReviwes']);
});


