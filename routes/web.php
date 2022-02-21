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
Route::get('/testing','LoanController@index')->name('loan');
Route::get('/photo', [App\Http\Controllers\PhotoController::class, 'index']);
Route::get('/homepage', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('throttle:5,1')->get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
