# PHP_Laravel12_Cloner

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.x-blue)

---

## Overview

**PHP_Laravel12_Cloner** is a Laravel 12 project that demonstrates a complete **CRUD (Create, Read, Update, Delete)** system with an additional **Product Cloner feature**.

The project uses the **bkwld/cloner package** to duplicate existing products easily.

Users can manage products through a simple admin-style interface.

---

## Features

* Product Create
* Product Edit
* Product Delete
* Product Clone
* Success Notifications
* Clean Table UI
* Laravel MVC Architecture
* Blade Template Views

---


## Project Structure

```
laravel-cloner-demo
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── ProductController.php
│   │
│   └── Models
│       └── Product.php
│
├── database
│   └── migrations
│       └── create_products_table.php
│
├── resources
│   └── views
│       └── products
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
│
└── routes
    └── web.php
```

---

## Step 1 — Create Laravel Project

Install Laravel using Composer.

composer create-project laravel/laravel laravel-cloner-demo

Run the Laravel development server.

php artisan serve

Open browser:

http://127.0.0.1:8000

---

## Step 2 — Configure Database

Open .env file.

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

---

## Step 3 — Install Cloner Package

Install the Laravel model cloning package.

composer require bkwld/cloner

Laravel automatically registers the package.

---

## Step 4 — Create Product Model and Migration

Run the command:

php artisan make:model Product -m

---

## Step 5 — Edit Migration

Open:

database/migrations/create_products_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

Run migration:

php artisan migrate

---

## Step 6 — Product Model

Open:

app/Models/Product.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bkwld\Cloner\Cloneable;

class Product extends Model
{
    use Cloneable;

    protected $fillable = [
        'name',
        'price',
        'description'
    ];
}
```

---

## Step 7 — Create Controller

Run:

php artisan make:controller ProductController

File:

app/Http/Controllers/ProductController.php

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    // Display all products
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index',compact('products'));
    }

    // Show product creation form
    public function create()
    {
        return view('products.create');
    }

    // Store new product in database
    public function store(Request $request)
    {

        Product::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description
        ]);

        return redirect('/products')->with('success','Product Added Successfully');
    }

    // Show edit form for selected product
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit',compact('product'));
    }

    // Update product details in database
    public function update(Request $request,$id)
    {

        $product = Product::findOrFail($id);

        $product->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description
        ]);

        return redirect('/products')->with('success','Product Updated Successfully');
    }

    // Delete selected product
    public function delete($id)
    {

        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->back()->with('success','Product Deleted Successfully');

    }

    // Clone (duplicate) the selected product
    public function clone($id)
    {

        $product = Product::findOrFail($id);

        $clone = $product->duplicate();

        $clone->name = $clone->name.' Copy';

        $clone->save();

        return redirect()->back()->with('success','Product Cloned Successfully');
    }

}
```

---

## Step 8 — Routes

Open:

routes/web.php

```php
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
```

---

## Step 9 — Create Views Folder

Create directory:

resources/views/products

---

## Step 10 — Product List Page

File:

resources/views/products/index.blade.php

```html
<!DOCTYPE html>
<html>
<head>

<title>Product List</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.container{
width:1100px;
margin:auto;
background:white;
padding:30px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;
}

h2{
margin-bottom:20px;
}

.add-btn{
background:#28a745;
color:white;
padding:10px 20px;
text-decoration:none;
border-radius:5px;
}

table{
width:100%;
margin-top:20px;
border-collapse:collapse;
}

table th{
background:#343a40;
color:white;
padding:12px;
}

table td{
padding:12px;
border-bottom:1px solid #ddd;
}

.success{
background:#d4edda;
padding:10px;
margin-bottom:15px;
border-radius:5px;
}

.action-buttons{
display:flex;
gap:10px;
}

.clone-btn{
background:#007bff;
color:white;
padding:6px 14px;
border-radius:4px;
text-decoration:none;
}

.edit-btn{
background:#ffc107;
color:black;
padding:6px 14px;
border-radius:4px;
text-decoration:none;
}

.delete-btn{
background:#dc3545;
color:white;
padding:6px 14px;
border-radius:4px;
text-decoration:none;
}

</style>

</head>

<body>

<div class="container">

<h2>Product List</h2>

@if(session('success'))
<div class="success">
{{ session('success') }}
</div>
@endif

<a class="add-btn" href="/products/create">Add Product</a>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Description</th>
<th>Action</th>
</tr>

@foreach($products as $product)

<tr>

<td>{{ $product->id }}</td>
<td>{{ $product->name }}</td>
<td>{{ $product->price }}</td>
<td>{{ $product->description }}</td>

<td>

<div class="action-buttons">

<a class="clone-btn" href="/products/clone/{{ $product->id }}">Clone</a>
<a class="edit-btn" href="/products/edit/{{ $product->id }}">Edit</a>
<a class="delete-btn" href="/products/delete/{{ $product->id }}">Delete</a>

</div>

</td>

</tr>

@endforeach

</table>

</div>

</body>
</html>
```

---

## Step 11 — Create Product Form

File:

resources/views/products/create.blade.php

```html
<!DOCTYPE html>
<html>
<head>

<title>Add Product</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.container{
width:500px;
margin:auto;
background:white;
padding:30px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;
}

input,textarea{
width:100%;
padding:10px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:4px;
}

button{
background:#007bff;
color:white;
padding:10px 20px;
border:none;
border-radius:4px;
}

</style>

</head>

<body>

<div class="container">

<h2>Add Product</h2>

<form method="POST" action="/products/store">

@csrf

<label>Name</label>
<input type="text" name="name">

<label>Price</label>
<input type="text" name="price">

<label>Description</label>
<textarea name="description"></textarea>

<button type="submit">Save Product</button>

</form>

</div>

</body>
</html>
```

---

## Step 12 — Edit Product Page

File:

resources/views/products/edit.blade.php

```html
<!DOCTYPE html>
<html>
<head>

<title>Edit Product</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.container{
width:500px;
margin:auto;
background:white;
padding:30px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;
}

input,textarea{
width:100%;
padding:10px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:4px;
}

button{
background:#007bff;
color:white;
padding:10px 20px;
border:none;
border-radius:4px;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Product</h2>

<form method="POST" action="/products/update/{{ $product->id }}">

@csrf

<label>Name</label>
<input type="text" name="name" value="{{ $product->name }}">

<label>Price</label>
<input type="text" name="price" value="{{ $product->price }}">

<label>Description</label>
<textarea name="description">{{ $product->description }}</textarea>

<button type="submit">Update Product</button>

</form>

</div>

</body>
</html>
```

---

## # Project Output

## 1. Start Laravel Server

Run the following command in the terminal:

```
php artisan serve
```

---

## 2. Open Browser

Open the following URL in your browser:

```
http://127.0.0.1:8000/products
```

---

## 3. Index Page

<img width="1158" height="320" alt="Screenshot 2026-03-09 113529" src="https://github.com/user-attachments/assets/4f6e3d49-3534-4628-be7e-8e3732d4a260" />

---
## 4. Add Product

<img width="562" height="421" alt="Screenshot 2026-03-09 112950" src="https://github.com/user-attachments/assets/3bf761c2-3fe4-47dd-9b50-2e33889f6900" />

---
<img width="1162" height="321" alt="Screenshot 2026-03-09 113223" src="https://github.com/user-attachments/assets/75e10344-2951-4095-9a63-4b45c4fb140a" />

---

## 5. Edit Product

<img width="558" height="390" alt="Screenshot 2026-03-09 113029" src="https://github.com/user-attachments/assets/26487612-ca54-44b6-ba23-d9b0359b0ccf" />

---
<img width="1160" height="320" alt="Screenshot 2026-03-09 113244" src="https://github.com/user-attachments/assets/de080744-8032-4a3e-ba12-92928118e65e" />

---

## 6. Clone Product

<img width="1164" height="426" alt="Screenshot 2026-03-09 113442" src="https://github.com/user-attachments/assets/442affa6-2d99-439b-a193-05517588b0fe" />

---
<img width="1161" height="489" alt="Screenshot 2026-03-09 113509" src="https://github.com/user-attachments/assets/e2897094-9b12-4beb-8c1e-8f91a198d350" />

---

## 7. Delete Product

<img width="1062" height="261" alt="Screenshot 2026-03-09 113140" src="https://github.com/user-attachments/assets/22314b4f-6a58-4eeb-adc3-87205283c940" />

---

## Conclusion

This project demonstrates:

* Laravel MVC structure
* CRUD implementation
* Model cloning using a package
* Blade UI design

It can serve as a basic admin panel example or Laravel learning project.


