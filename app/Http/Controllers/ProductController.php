<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use App\Models\CloneBlueprint;
use App\Models\CloneAuditLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    /**
     * Display products with search, filters, sorting and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $cloneStatus = $request->input('clone_status');

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'asc');

        $perPage = (int) $request->input('per_page', 5);

        /*
        |--------------------------------------------------------------------------
        | Allowed sorting columns
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'name',
            'price',
            'created_at',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        /*
        |--------------------------------------------------------------------------
        | Allowed pagination values
        |--------------------------------------------------------------------------
        */

        if (!in_array($perPage, [5, 10, 25, 50])) {
            $perPage = 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Product Query
        |--------------------------------------------------------------------------
        */

        $products = Product::with('originalProduct')
            ->withCount('clones')

            ->when($search, function ($query, $search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere(
                            'description',
                            'like',
                            '%' . $search . '%'
                        );
                });
            })

            ->when(
                $minPrice !== null && $minPrice !== '',
                function ($query) use ($minPrice) {

                    $query->whereRaw(
                        'CAST(price AS DECIMAL(12,2)) >= ?',
                        [$minPrice]
                    );
                }
            )

            ->when(
                $maxPrice !== null && $maxPrice !== '',
                function ($query) use ($maxPrice) {

                    $query->whereRaw(
                        'CAST(price AS DECIMAL(12,2)) <= ?',
                        [$maxPrice]
                    );
                }
            )

            ->when($cloneStatus === 'original', function ($query) {

                $query->whereNull('cloned_from_id');
            })

            ->when($cloneStatus === 'cloned', function ($query) {

                $query->whereNotNull('cloned_from_id');
            })

            ->orderBy($sort, $direction)

            ->paginate($perPage)

            ->withQueryString();

        return view('products.index', compact(
            'products',
            'search',
            'minPrice',
            'maxPrice',
            'cloneStatus',
            'sort',
            'direction',
            'perPage'
        ));
    }


    /**
     * Product dashboard with statistics.
     */
    public function dashboard()
    {
        $totalProducts = Product::count();

        $clonedProducts = Product::whereNotNull('cloned_from_id')
            ->count();

        $originalProducts = Product::whereNull('cloned_from_id')
            ->count();

        $totalCloneOperations = $clonedProducts;

        /*
        |--------------------------------------------------------------------------
        | Clone Rate
        |--------------------------------------------------------------------------
        */

        $cloneRate = $totalProducts > 0
            ? round(($clonedProducts / $totalProducts) * 100, 2)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Price Statistics
        |--------------------------------------------------------------------------
        */

        $totalPrice = Product::sum('price');

        $averagePrice = Product::avg('price');

        $minimumPrice = Product::min('price');

        $maximumPrice = Product::max('price');


        /*
        |--------------------------------------------------------------------------
        | Price Range Counts
        |--------------------------------------------------------------------------
        */

        $lowPriceProducts = Product::where('price', '<', 5000)
            ->count();

        $mediumPriceProducts = Product::whereBetween(
            'price',
            [5000, 20000]
        )->count();

        $highPriceProducts = Product::where('price', '>', 20000)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Most Cloned Products
        |--------------------------------------------------------------------------
        */

        $productsWithMostClones = Product::whereNull('cloned_from_id')
            ->withCount('clones')
            ->having('clones_count', '>', 0)
            ->orderByDesc('clones_count')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Top 5 Expensive Products
        |--------------------------------------------------------------------------
        */

        $topExpensiveProducts = Product::orderByDesc('price')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Products
        |--------------------------------------------------------------------------
        */

        $recentProducts = Product::oldest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Clone Activity
        |--------------------------------------------------------------------------
        */

        $recentClones = Product::with('originalProduct')
            ->whereNotNull('cloned_from_id')
            ->oldest()
            ->limit(5)
            ->get();


        return view('products.dashboard', compact(
            'totalProducts',
            'clonedProducts',
            'originalProducts',
            'totalCloneOperations',
            'cloneRate',
            'totalPrice',
            'averagePrice',
            'minimumPrice',
            'maximumPrice',
            'lowPriceProducts',
            'mediumPriceProducts',
            'highPriceProducts',
            'productsWithMostClones',
            'topExpensiveProducts',
            'recentProducts',
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
     * Store new product.
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
                'Product Cloned Successfully from "' .
                $product->name .
                '"'
            );
    }


    /**
     * Bulk delete products.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $count = Product::whereIn(
            'id',
            $validated['product_ids']
        )->delete();

        return redirect('/products')
            ->with(
                'success',
                $count . ' product(s) deleted successfully.'
            );
    }


    /**
     * Bulk clone products.
     */
    public function bulkClone(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $products = Product::whereIn(
            'id',
            $validated['product_ids']
        )->get();

        $count = 0;

        foreach ($products as $product) {

            $clone = $product->duplicate();

            $clone->name = $product->name . ' Copy';

            $clone->cloned_from_id = $product->id;

            $clone->save();

            $count++;
        }

        return redirect('/products')
            ->with(
                'success',
                $count . ' product(s) cloned successfully.'
            );
    }


    /**
     * Export products to CSV.
     *
     * Export respects the current search and filters.
     */
    public function export(Request $request)
    {
        $search = $request->input('search');

        $minPrice = $request->input('min_price');

        $maxPrice = $request->input('max_price');

        $cloneStatus = $request->input('clone_status');


        $products = Product::with('originalProduct')
            ->when($search, function ($query, $search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere(
                            'description',
                            'like',
                            '%' . $search . '%'
                        );
                });
            })

            ->when(
                $minPrice !== null && $minPrice !== '',
                function ($query) use ($minPrice) {

                    $query->whereRaw(
                        'CAST(price AS DECIMAL(12,2)) >= ?',
                        [$minPrice]
                    );
                }
            )

            ->when(
                $maxPrice !== null && $maxPrice !== '',
                function ($query) use ($maxPrice) {

                    $query->whereRaw(
                        'CAST(price AS DECIMAL(12,2)) <= ?',
                        [$maxPrice]
                    );
                }
            )

            ->when($cloneStatus === 'original', function ($query) {

                $query->whereNull('cloned_from_id');
            })

            ->when($cloneStatus === 'cloned', function ($query) {

                $query->whereNotNull('cloned_from_id');
            })

            ->oldest()
            ->get();


        $filename = 'products_' . now()->format('Y-m-d_H-i-s') . '.csv';


        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' .
                $filename .
                '"',
        ];


        $callback = function () use ($products) {

            $file = fopen('php://output', 'w');


            fputcsv($file, [
                'ID',
                'Name',
                'Price',
                'Description',
                'Type',
                'Cloned From',
                'Created At',
            ]);


            foreach ($products as $product) {

                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->price,
                    $product->description,
                    $product->cloned_from_id
                        ? 'Cloned'
                        : 'Original',
                    $product->originalProduct?->name ?? '',
                    $product->created_at?->format(
                        'Y-m-d H:i:s'
                    ),
                ]);
            }


            fclose($file);
        };


        return Response::stream(
            $callback,
            200,
            $headers
        );
    }


    /**
     * Display clone history.
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

                    $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhereHas(
                        'originalProduct',
                        function ($query) use ($search) {

                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
                });
            })

            ->when($fromDate, function ($query, $fromDate) {

                $query->whereDate(
                    'created_at',
                    '>=',
                    $fromDate
                );
            })

            ->when($toDate, function ($query, $toDate) {

                $query->whereDate(
                    'created_at',
                    '<=',
                    $toDate
                );
            })

            ->oldest()

            ->paginate(5)

            ->withQueryString();


        return view(
            'products.clone-history',
            compact(
                'cloneHistory',
                'search',
                'fromDate',
                'toDate'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Deep Multi-Level Relational Clone Studio & Smart Field Replacer View
    |--------------------------------------------------------------------------
    */
    public function deepCloneStudio(Request $request)
    {
        $products = Product::with(['variants.attributes', 'clones'])
            ->latest()
            ->paginate(10);

        $blueprints = CloneBlueprint::latest()->get();

        return view('products.deep_clone_studio', compact('products', 'blueprints'));
    }

    /*
    |--------------------------------------------------------------------------
    | Execute Deep Multi-Level Relational Clone
    |--------------------------------------------------------------------------
    */
    public function deepClone(Request $request, $id)
    {
        $product = Product::with('variants.attributes')->findOrFail($id);

        $prefix = $request->input('prefix', 'Copy of ');
        $suffix = $request->input('suffix', '');
        $skuPrefix = $request->input('sku_prefix', 'CLONE-');
        $priceMultiplier = (float) $request->input('price_multiplier', 1.0);
        $resetStock = $request->boolean('reset_stock');
        $blueprintId = $request->input('blueprint_id');

        $batchId = 'BATCH-' . date('YmdHis') . '-' . rand(100, 999);

        // 1. Deep Duplicate Main Product
        $clonedProduct = $product->replicate();
        $clonedProduct->name = trim($prefix . $product->name . ' ' . $suffix);
        $clonedProduct->sku = $skuPrefix . ($product->sku ?: rand(1000, 9999));
        $clonedProduct->price = round((float)$product->price * $priceMultiplier, 2);
        $clonedProduct->stock = $resetStock ? 0 : $product->stock;
        $clonedProduct->cloned_from_id = $product->id;
        $clonedProduct->save();

        $relationsCount = 0;

        // 2. Deep Duplicate Child Variants & Nested Grandchild Attributes
        foreach ($product->variants as $variant) {
            $clonedVariant = $variant->replicate();
            $clonedVariant->product_id = $clonedProduct->id;
            $clonedVariant->sku = 'VAR-' . rand(10000, 99999);
            $clonedVariant->save();
            $relationsCount++;

            foreach ($variant->attributes as $attr) {
                $clonedAttr = $attr->replicate();
                $clonedAttr->product_variant_id = $clonedVariant->id;
                $clonedAttr->save();
                $relationsCount++;
            }
        }

        // 3. Record Clone Audit Log
        CloneAuditLog::create([
            'batch_id' => $batchId,
            'original_product_id' => $product->id,
            'cloned_product_id' => $clonedProduct->id,
            'blueprint_name' => $blueprintId ? (CloneBlueprint::find($blueprintId)->name ?? 'Custom Deep Clone') : 'Custom Deep Clone',
            'relations_cloned_count' => $relationsCount,
        ]);

        return redirect()->route('products.deep-clone-studio')->with('success', "⚡ Deep Cloned '{$product->name}' with {$relationsCount} nested relations! Batch ID: {$batchId}");
    }

    /*
    |--------------------------------------------------------------------------
    | Clone Blueprint Presets & Rollback Inspector View
    |--------------------------------------------------------------------------
    */
    public function blueprints(Request $request)
    {
        $blueprints = CloneBlueprint::latest()->get();

        $auditLogs = CloneAuditLog::with(['originalProduct', 'clonedProduct'])
            ->latest()
            ->paginate(10);

        return view('products.blueprints', compact('blueprints', 'auditLogs'));
    }

    /*
    |--------------------------------------------------------------------------
    | Save Custom Clone Blueprint Preset
    |--------------------------------------------------------------------------
    */
    public function saveBlueprint(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2',
            'prefix_rule' => 'nullable|string',
            'status_override' => 'required|string',
        ]);

        $key = 'preset_' . Str::slug($request->name);

        CloneBlueprint::create([
            'name' => $request->name,
            'preset_key' => $key,
            'clone_relations' => $request->input('clone_relations', ['variants', 'attributes']),
            'prefix_rule' => $request->input('prefix_rule', 'Copy of '),
            'suffix_rule' => $request->input('suffix_rule', ''),
            'price_modifier_percentage' => (float) $request->input('price_modifier_percentage', 0.0),
            'reset_stock_to_zero' => $request->boolean('reset_stock_to_zero'),
            'status_override' => $request->input('status_override', 'draft'),
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('products.blueprints')->with('success', "🧬 Saved Clone Blueprint Preset '{$request->name}' successfully!");
    }

    /*
    |--------------------------------------------------------------------------
    | Revert / Rollback Cloned Batch
    |--------------------------------------------------------------------------
    */
    public function rollbackBatch(Request $request, $batch_id)
    {
        $logs = CloneAuditLog::where('batch_id', $batch_id)->get();

        if ($logs->isEmpty()) {
            return redirect()->route('products.blueprints')->with('error', "No audit records found for Batch ID: {$batch_id}");
        }

        $revertedCount = 0;
        foreach ($logs as $log) {
            if ($log->clonedProduct) {
                $log->clonedProduct->delete();
                $revertedCount++;
            }
            $log->delete();
        }

        return redirect()->route('products.blueprints')->with('success', "🔄 Successfully rolled back and deleted {$revertedCount} cloned record(s) from Batch '{$batch_id}'!");
    }
}