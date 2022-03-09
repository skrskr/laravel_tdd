<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CheckoutBookController;
use App\Http\Controllers\CheckinBookController;
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

Route::post('/books', [BookController::class, 'store']);
Route::patch('/books/{book}-{slug}', [BookController::class, 'update']);
Route::delete('/books/{book}-{slug}', [BookController::class, 'destroy']);

Route::post('/authors', [AuthorController::class, 'store']);

Route::post('/checkout/{book}', [CheckoutBookController::class, 'store']);
Route::post('/checkin/{book}', [CheckinBookController::class, 'store']);


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
