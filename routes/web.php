<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('data.index', [DataController::class, 'index'])->name('data');
Route::post('data.store', [DataController::class, 'store'])->name('data.store');
Route::post('data.edit', [DataController::class, 'edit'])->name('data.edit');
Route::post('data.update', [DataController::class, 'update'])->name('data.update');
Route::post('data.delete', [DataController::class, 'destroy'])->name('data.delete');
