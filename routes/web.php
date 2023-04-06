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

Route::get('/', function () {

    return view('welcome');
});

Auth::routes();

Route::group(['middleware'=> 'auth'],function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/breastcancer', function () {
        return view('breastcancer.breastcancer');
    })->name('breastcancer');
    

    Route::resource('doctors',\App\Http\Controllers\DoctorController::class);

    
    Route::get('/breastcancer/article', [\App\Http\Controllers\ArticleController::class, 'index'])->name('article.index');
    Route::get('/breastcancer/article/create', [\App\Http\Controllers\ArticleController::class, 'create'])->name('article.create');
    Route::post('/breastcancer/article', [\App\Http\Controllers\ArticleController::class, 'store'])->name('article.store');
    Route::get('/breastcancer/article/{article}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('article.show');
    Route::get('/breastcancer/article/{article}/edit', [\App\Http\Controllers\ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/breastcancer/article/{article}', [\App\Http\Controllers\ArticleController::class, 'update'])->name('article.update');
    Route::delete('/breastcancer/article/{article}', [\App\Http\Controllers\ArticleController::class, 'destroy'])->name('article.destroy');
});

