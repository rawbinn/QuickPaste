<?php

use App\Http\Controllers\PasteController;
use Illuminate\Support\Facades\Route;

// Show the form to create a paste
Route::get('/', [PasteController::class, 'createForm'])->name('paste.create');

// Handle paste submission
Route::post('/create', [PasteController::class, 'store'])->name('paste.store');

// Handle viewing the paste (including password checks, expiry, etc.)
Route::match(['get', 'post'], '/{code}', [PasteController::class, 'show'])->name('paste.show');
