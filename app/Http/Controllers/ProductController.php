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