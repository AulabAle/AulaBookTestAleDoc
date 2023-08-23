<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PublicController::class, 'home'])->name('welcome');
Route::get('/create/book', [BookController::class, 'create'])->name('book.create');
Route::get('/index/book', [BookController::class, 'index'])->name('book.index');
Route::get('/show/book/{book}', [BookController::class, 'show'])->name('book.show');
Route::get('/download/book/{book}', [BookController::class, 'downloadBook'])->name('book.download');
Route::get('/view/book/{book}', [BookController::class, 'viewPdf'])->name('book.viewPdf');


Route::post('/checkout/{book}', [PaymentController::class, 'checkout'])->middleware('auth')->name('checkout');
Route::get('/checkout/success/{purchasedBook}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/checkout/cancel/{purchasedBook}', [PaymentController::class, 'cancel'])->name('payment.cancel');