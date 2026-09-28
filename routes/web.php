<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

// Product dashboard
Route::get('/products/dashboard', [ProductController::class, 'dashboard'])
    ->name('products.dashboard');

// Product clone history
Route::get('/products/clone-history', [ProductController::class, 'cloneHistory'])
    ->name('products.clone-history');

// Display products
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

// Show create form
Route::get('/products/create', [ProductController::class, 'create'])
    ->name('products.create');

// Store product
Route::post('/products/store', [ProductController::class, 'store'])
    ->name('products.store');

// Show edit form
Route::get('/products/edit/{id}', [ProductController::class, 'edit'])
    ->name('products.edit');

// Update product
Route::post('/products/update/{id}', [ProductController::class, 'update'])
    ->name('products.update');

// Delete product
Route::get('/products/delete/{id}', [ProductController::class, 'delete'])
    ->name('products.delete');

// Clone product
Route::get('/products/clone/{id}', [ProductController::class, 'clone'])
    ->name('products.clone');