<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Product Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/dashboard',
    [ProductController::class, 'dashboard']
)->name('products.dashboard');


/*
|--------------------------------------------------------------------------
| Product Clone History
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/clone-history',
    [ProductController::class, 'cloneHistory']
)->name('products.clone-history');


/*
|--------------------------------------------------------------------------
| Product Export
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/export',
    [ProductController::class, 'export']
)->name('products.export');


/*
|--------------------------------------------------------------------------
| Deep Multi-Level Relational Clone Studio & Smart Field Replacer
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/deep-clone-studio',
    [ProductController::class, 'deepCloneStudio']
)->name('products.deep-clone-studio');

Route::post(
    '/products/deep-clone/{id}',
    [ProductController::class, 'deepClone']
)->name('products.deep-clone');


/*
|--------------------------------------------------------------------------
| Clone Blueprint Presets & Rollback Inspector
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/blueprints',
    [ProductController::class, 'blueprints']
)->name('products.blueprints');

Route::post(
    '/products/blueprints/save',
    [ProductController::class, 'saveBlueprint']
)->name('products.blueprints.save');

Route::post(
    '/products/rollback-batch/{batch_id}',
    [ProductController::class, 'rollbackBatch']
)->name('products.rollback-batch');



/*
|--------------------------------------------------------------------------
| Bulk Product Operations
|--------------------------------------------------------------------------
*/

Route::post(
    '/products/bulk-delete',
    [ProductController::class, 'bulkDelete']
)->name('products.bulk-delete');


Route::post(
    '/products/bulk-clone',
    [ProductController::class, 'bulkClone']
)->name('products.bulk-clone');


/*
|--------------------------------------------------------------------------
| Product List
|--------------------------------------------------------------------------
*/

Route::get(
    '/products',
    [ProductController::class, 'index']
)->name('products.index');


/*
|--------------------------------------------------------------------------
| Create Product
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/create',
    [ProductController::class, 'create']
)->name('products.create');


Route::post(
    '/products/store',
    [ProductController::class, 'store']
)->name('products.store');


/*
|--------------------------------------------------------------------------
| Edit Product
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/edit/{id}',
    [ProductController::class, 'edit']
)->name('products.edit');


Route::post(
    '/products/update/{id}',
    [ProductController::class, 'update']
)->name('products.update');


/*
|--------------------------------------------------------------------------
| Delete Product
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/delete/{id}',
    [ProductController::class, 'delete']
)->name('products.delete');


/*
|--------------------------------------------------------------------------
| Clone Product
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/clone/{id}',
    [ProductController::class, 'clone']
)->name('products.clone');