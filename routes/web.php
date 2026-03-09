<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Display product list
Route::get('/products',[ProductController::class,'index']);

// Show form to create a new product
Route::get('/products/create',[ProductController::class,'create']);

// Store new product in database
Route::post('/products/store',[ProductController::class,'store']);

// Show edit form for selected product
Route::get('/products/edit/{id}',[ProductController::class,'edit']);

// Update product details in database
Route::post('/products/update/{id}',[ProductController::class,'update']);

// Delete selected product
Route::get('/products/delete/{id}',[ProductController::class,'delete']);

// Clone (duplicate) selected product
Route::get('/products/clone/{id}',[ProductController::class,'clone']);