<?php

use App\Http\Controllers\PasteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PasteController::class, 'createForm'])->name('paste.create');
Route::post('/create', [PasteController::class, 'store'])->name('paste.store');

Route::match(['get', 'post'], '/{code}', [PasteController::class, 'show'])
    ->where('code', '[A-Za-z0-9]{6}')
    ->name('paste.show');