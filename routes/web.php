<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\UploadController;

use Illuminate\Support\Facades\Route;




Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/sample/download', [ProductController::class, 'downloadSample'])->name('products.sample.download');
    Route::get('/products/{product}/images', [ProductController::class, 'images'])->name('products.images');
    Route::post('/products/{product}/set-primary', [ProductController::class, 'setPrimary'])->name('product.setPrimary');

    // ✅ ADD NAME HERE

    Route::post('/products/import', [ProductImportController::class, 'store'])->name('products.import');
    Route::get('/products/import/{import}/progress', [ProductImportController::class, 'progress'])->name('products.import.progress');

    Route::post('/upload/chunk', [UploadController::class, 'chunk'])->name('upload.chunk');
    Route::post('/upload/{product}/complete', [UploadController::class, 'complete'])->name('upload.complete');

    Route::post('/image/{image}/primary', [ProductController::class, 'primary'])->name('image.primary');
});
require __DIR__.'/auth.php';
