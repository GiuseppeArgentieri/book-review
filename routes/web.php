<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    return redirect()->route('books.index');
});

Route::resource('books', BookController::class)
    ->only(['index', 'show']); // tutte le altre azioni sono disabilitate

Route::resource('books.reviews', ReviewController::class)
    ->scoped(['review'=>'book'])
    ->only(['create', 'store'])
    ->middleware(['throttle:reviews']);
