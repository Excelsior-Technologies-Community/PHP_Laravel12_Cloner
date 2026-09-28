<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display products with search, filtering and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $cloneStatus = $request->input('clone_status');

        $products = Product::with('originalProduct')
            ->withCount('clones')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($minPrice !== null && $minPrice !== '', function ($query) use ($minPrice) {
                $query->whereRaw(
                    'CAST(price AS DECIMAL(12,2)) >= ?',
                    [$minPrice]
                );
            })
            ->when($maxPrice !== null && $maxPrice !== '', function ($query) use ($maxPrice) {
                $query->whereRaw(
                    'CAST(price AS DECIMAL(12,2)) <= ?',
                    [$maxPrice]
                );
            })
            ->when($cloneStatus === 'original', function ($query) {
                $query->whereNull('cloned_from_id');
            })
            ->when($cloneStatus === 'cloned', function ($query) {
                $query->whereNotNull('cloned_from_id');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact(
            'products',
            'search',
            'minPrice',
            'maxPrice',
            'cloneStatus'
        ));
    }

    /**
     * Display clone dashboard and statistics.
     */
    public function dashboard()
    {
        $totalProducts = Product::count();

        $clonedProducts = Product::whereNotNull('cloned_from_id')->count();

        $originalProducts = Product::whereNull('cloned_from_id')->count();

        $totalCloneOperations = $clonedProducts;

        $productsWithMostClones = Product::whereNull('cloned_from_id')
            ->withCount('clones')
            ->having('clones_count', '>', 0)
            ->orderByDesc('clones_count')
            ->limit(5)
            ->get();

        $recentClones = Product::with('originalProduct')
            ->whereNotNull('cloned_from_id')
            ->latest()
            ->limit(5)
            ->get();

        return view('products.dashboard', compact(
            'totalProducts',
            'clonedProducts',
            'originalProducts',
            'totalCloneOperations',
            'productsWithMostClones',
            'recentClones'
        ));
    }

    /**
     * Show product creation form.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'cloned_from_id' => null,
        ]);

        return redirect('/products')
            ->with('success', 'Product Added Successfully');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect('/products')
            ->with('success', 'Product Updated Successfully');
    }

    /**
     * Delete selected product.
     */
    public function delete($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect('/products')
            ->with('success', 'Product Deleted Successfully');
    }

    /**
     * Clone selected product.
     */
    public function clone($id)
    {
        $product = Product::findOrFail($id);

        $clone = $product->duplicate();

        $clone->name = $product->name . ' Copy';
        $clone->cloned_from_id = $product->id;

        $clone->save();

        return redirect('/products')
            ->with(
                'success',
                'Product Cloned Successfully from "' . $product->name . '"'
            );
    }

    /**
     * Display clone history with search and date filtering.
     */
    public function cloneHistory(Request $request)
    {
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $cloneHistory = Product::with('originalProduct')
            ->whereNotNull('cloned_from_id')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('originalProduct', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($fromDate, function ($query, $fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query, $toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('products.clone-history', compact(
            'cloneHistory',
            'search',
            'fromDate',
            'toDate'
        ));
    }
}