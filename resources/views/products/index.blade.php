<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Management - Laravel Cloner</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f6f9;
        }

        .container-main {
            max-width: 1250px;
            margin: 40px auto;
        }

        .card {
            border: none;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .table th {
            white-space: nowrap;
        }

        .description {
            max-width: 220px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .filter-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

<div class="container-main">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h2 class="mb-1">Product Management</h2>
            <p class="text-muted mb-0">
                Manage, search and clone products
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('products.dashboard') }}"
               class="btn btn-dark">
                📊 Dashboard
            </a>

            <a href="{{ route('products.clone-history') }}"
               class="btn btn-info text-white">
                🕒 Clone History
            </a>

            <a href="{{ route('products.create') }}"
               class="btn btn-success">
                + Add Product
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- Search & Filter -->

    <div class="filter-card">

        <form method="GET" action="{{ route('products.index') }}">

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">
                        Search Product
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search name or description..."
                        value="{{ $search }}"
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Min Price
                    </label>

                    <input
                        type="number"
                        name="min_price"
                        class="form-control"
                        min="0"
                        step="0.01"
                        placeholder="0"
                        value="{{ $minPrice }}"
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Max Price
                    </label>

                    <input
                        type="number"
                        name="max_price"
                        class="form-control"
                        min="0"
                        step="0.01"
                        placeholder="100000"
                        value="{{ $maxPrice }}"
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Product Type
                    </label>

                    <select name="clone_status" class="form-select">

                        <option value="">
                            All Products
                        </option>

                        <option
                            value="original"
                            {{ $cloneStatus === 'original' ? 'selected' : '' }}
                        >
                            Original Products
                        </option>

                        <option
                            value="cloned"
                            {{ $cloneStatus === 'cloned' ? 'selected' : '' }}
                        >
                            Cloned Products
                        </option>

                    </select>

                </div>


                <div class="col-md-2 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary w-100">
                        Search
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-outline-secondary">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    <!-- Product Table -->

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Price</th>

                            <th>Description</th>

                            <th>Type</th>

                            <th>Cloned From</th>

                            <th>Clone Count</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($products as $product)

                        <tr>

                            <td>
                                {{ $product->id }}
                            </td>


                            <td>

                                <strong>
                                    {{ $product->name }}
                                </strong>

                            </td>


                            <td>

                                ₹{{ number_format((float) $product->price, 2) }}

                            </td>


                            <td class="description">

                                {{ $product->description ?: 'No description' }}

                            </td>


                            <td>

                                @if($product->cloned_from_id)

                                    <span class="badge bg-info">
                                        Cloned
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Original
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($product->originalProduct)

                                    <span class="text-primary">

                                        {{ $product->originalProduct->name }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-secondary">

                                    {{ $product->clones_count }}

                                </span>

                            </td>


                            <td>

                                {{ $product->created_at->format('d M Y') }}

                            </td>


                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('products.clone', $product->id) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        Clone
                                    </a>


                                    <a
                                        href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Edit
                                    </a>


                                    <a
                                        href="{{ route('products.delete', $product->id) }}"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this product?')"
                                    >
                                        Delete
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center py-5">

                                <h5>No products found</h5>

                                <p class="text-muted mb-0">
                                    Try changing your search or filters.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($products->hasPages())

            <div class="card-footer bg-white">

                {{ $products->links('pagination::bootstrap-5') }}

            </div>

        @endif

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>