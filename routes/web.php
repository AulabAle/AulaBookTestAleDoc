<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RevisorController;

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
Route::get('/index/book/category/{category}', [BookController::class, 'indexCategory'])->name('book.category');
//Pubblica book
Route::patch('/accetta-book/{book}', [BookController::class, 'publish'])->name('user.publish');
//Nascondi book
Route::patch('/rifiuta-book/{book}', [BookController::class, 'unpublish'])->name('user.unpublish');


Route::post('/checkout/{book}', [PaymentController::class, 'checkout'])->middleware('auth')->name('checkout');
Route::get('/checkout/success/{purchasedBook}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/checkout/cancel/{purchasedBook}', [PaymentController::class, 'cancel'])->name('payment.cancel');


Route::get('/diventa-revisore', [RevisorController::class, 'becomeRevisor'])->middleware('auth')->name('become.revisor');
Route::get('/rendi-revisore/{user}', [RevisorController::class, 'makeRevisor'])->name('make.revisor');
Route::get('/revisor/home' , [RevisorController::class, 'index'])->middleware('isRevisor')->name('revisor.index');

Route::post('/revisione-book/{book}', [ReviewController::class, 'store'])->name('response.review');

Route::get('/user/profile' , [UserController::class , 'userProfile'])->name('user.profile');

Route::delete('/delete/book/{book}', [BookController::class, 'destroy'])->name('book.destroy');
Route::get('/edit/book/{book}', [BookController::class, 'edit'])->name('book.edit');


Route::post('/books/{book}/comments', [CommentController::class, 'store'])->middleware('auth')->name('comments.store');

