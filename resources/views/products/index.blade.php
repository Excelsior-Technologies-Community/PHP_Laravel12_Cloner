<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

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
            max-width: 1350px;
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

        .bulk-card {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

<div class="container-main">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>

            <h2 class="mb-1">
                Product Management
            </h2>

            <p class="text-muted mb-0">
                Manage, search, filter, clone and export products
            </p>

        </div>


        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route('products.dashboard') }}"
                class="btn btn-dark"
            >
                📊 Dashboard
            </a>


            <a
                href="{{ route('products.clone-history') }}"
                class="btn btn-info text-white"
            >
                🕒 Clone History
            </a>


            <a
                href="{{ route('products.create') }}"
                class="btn btn-success"
            >
                + Add Product
            </a>

        </div>

    </div>


    <!-- Success Message -->

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


    <!-- Validation Errors -->

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- Search & Filter -->

    <div class="filter-card">

        <form
            method="GET"
            action="{{ route('products.index') }}"
        >

            <div class="row g-3">

                <div class="col-md-3">

                    <label class="form-label">
                        Search Product
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Name or description..."
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

                    <select
                        name="clone_status"
                        class="form-select"
                    >

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


                <div class="col-md-2">

                    <label class="form-label">
                        Sort By
                    </label>

                    <select
                        name="sort"
                        class="form-select"
                    >

                        <option
                            value="created_at"
                            {{ $sort === 'created_at' ? 'selected' : '' }}
                        >
                            Created Date
                        </option>

                        <option
                            value="name"
                            {{ $sort === 'name' ? 'selected' : '' }}
                        >
                            Name
                        </option>

                        <option
                            value="price"
                            {{ $sort === 'price' ? 'selected' : '' }}
                        >
                            Price
                        </option>

                        <option
                            value="id"
                            {{ $sort === 'id' ? 'selected' : '' }}
                        >
                            ID
                        </option>

                    </select>

                </div>


                <div class="col-md-1">

                    <label class="form-label">
                        Order
                    </label>

                    <select
                        name="direction"
                        class="form-select"
                    >

                        <option
                            value="desc"
                            {{ $direction === 'desc' ? 'selected' : '' }}
                        >
                            ↓
                        </option>

                        <option
                            value="asc"
                            {{ $direction === 'asc' ? 'selected' : '' }}
                        >
                            ↑
                        </option>

                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Per Page
                    </label>

                    <select
                        name="per_page"
                        class="form-select"
                    >

                        <option
                            value="5"
                            {{ $perPage == 5 ? 'selected' : '' }}
                        >
                            5
                        </option>

                        <option
                            value="10"
                            {{ $perPage == 10 ? 'selected' : '' }}
                        >
                            10
                        </option>

                        <option
                            value="25"
                            {{ $perPage == 25 ? 'selected' : '' }}
                        >
                            25
                        </option>

                        <option
                            value="50"
                            {{ $perPage == 50 ? 'selected' : '' }}
                        >
                            50
                        </option>

                    </select>

                </div>


                <div class="col-md-4 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Search / Filter
                    </button>


                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>


                    <a
                        href="{{ route('products.export', request()->query()) }}"
                        class="btn btn-success"
                    >
                        📥 CSV
                    </a>

                </div>

            </div>

        </form>

    </div>


    <!-- Bulk Actions -->

    <div class="bulk-card">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>

                <strong>
                    Bulk Actions
                </strong>

                <span
                    id="selectedCount"
                    class="badge bg-secondary ms-2"
                >
                    0 Selected
                </span>

            </div>


            <div class="d-flex gap-2">

                <button
                    type="button"
                    id="selectAllButton"
                    class="btn btn-outline-primary btn-sm"
                >
                    Select All
                </button>


                <button
                    type="button"
                    id="clearAllButton"
                    class="btn btn-outline-secondary btn-sm"
                >
                    Clear
                </button>

            </div>

        </div>

    </div>


    <!-- Bulk Delete Form -->

    <form
        method="POST"
        action="{{ route('products.bulk-delete') }}"
        id="bulkDeleteForm"
        onsubmit="return confirm('Are you sure you want to delete the selected products?')"
    >

        @csrf

        <div class="card mb-3">

            <div class="card-body p-3">

                <div class="d-flex justify-content-between align-items-center">

                    <span class="text-muted">
                        Select products below for bulk operations.
                    </span>

                    <button
                        type="submit"
                        class="btn btn-danger btn-sm"
                    >
                        🗑 Bulk Delete
                    </button>

                </div>

            </div>

        </div>

    </form>


    <!-- Bulk Clone Form -->

    <form
        method="POST"
        action="{{ route('products.bulk-clone') }}"
        id="bulkCloneForm"
        onsubmit="return confirm('Clone all selected products?')"
    >

        @csrf

        <!-- Product Table -->

        <div class="card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    <input
                                        type="checkbox"
                                        id="masterCheckbox"
                                    >
                                </th>

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

                                    <input
                                        type="checkbox"
                                        name="product_ids[]"
                                        value="{{ $product->id }}"
                                        class="product-checkbox"
                                        form="bulkCloneForm"
                                    >

                                </td>


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

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <h5>
                                        No products found
                                    </h5>

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


        <div class="mt-3 text-end">

            <button
                type="submit"
                class="btn btn-primary"
            >
                📋 Bulk Clone Selected
            </button>

        </div>

    </form>

</div>


<script>

    const checkboxes =
        document.querySelectorAll('.product-checkbox');

    const masterCheckbox =
        document.getElementById('masterCheckbox');

    const selectAllButton =
        document.getElementById('selectAllButton');

    const clearAllButton =
        document.getElementById('clearAllButton');

    const selectedCount =
        document.getElementById('selectedCount');


    function updateSelectedCount()
    {
        const selected =
            document.querySelectorAll(
                '.product-checkbox:checked'
            ).length;

        selectedCount.textContent =
            selected + ' Selected';
    }


    masterCheckbox.addEventListener(
        'change',
        function ()
        {
            checkboxes.forEach(function (checkbox)
            {
                checkbox.checked =
                    masterCheckbox.checked;
            });

            updateSelectedCount();
        }
    );


    checkboxes.forEach(function (checkbox)
    {
        checkbox.addEventListener(
            'change',
            updateSelectedCount
        );
    });


    selectAllButton.addEventListener(
        'click',
        function ()
        {
            checkboxes.forEach(function (checkbox)
            {
                checkbox.checked = true;
            });

            masterCheckbox.checked = true;

            updateSelectedCount();
        }
    );


    clearAllButton.addEventListener(
        'click',
        function ()
        {
            checkboxes.forEach(function (checkbox)
            {
                checkbox.checked = false;
            });

            masterCheckbox.checked = false;

            updateSelectedCount();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Add selected products to bulk delete form
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('bulkDeleteForm')
        .addEventListener(
            'submit',
            function (event)
            {
                const selected =
                    document.querySelectorAll(
                        '.product-checkbox:checked'
                    );

                if (selected.length === 0)
                {
                    event.preventDefault();

                    alert(
                        'Please select at least one product.'
                    );

                    return;
                }

                selected.forEach(function (checkbox)
                {
                    const hidden =
                        document.createElement('input');

                    hidden.type = 'hidden';

                    hidden.name = 'product_ids[]';

                    hidden.value = checkbox.value;

                    document
                        .getElementById('bulkDeleteForm')
                        .appendChild(hidden);
                });
            }
        );


    /*
    |--------------------------------------------------------------------------
    | Validate bulk clone
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('bulkCloneForm')
        .addEventListener(
            'submit',
            function (event)
            {
                const selected =
                    document.querySelectorAll(
                        '.product-checkbox:checked'
                    );

                if (selected.length === 0)
                {
                    event.preventDefault();

                    alert(
                        'Please select at least one product.'
                    );
                }
            }
        );

</script>


</body>

</html>