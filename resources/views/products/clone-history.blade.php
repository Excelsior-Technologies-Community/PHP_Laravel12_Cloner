<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Clone History</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f6f9;
        }

        .container-main {
            max-width: 1200px;
            margin: 40px auto;
        }

        .card {
            border: none;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

<div class="container-main">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>🕒 Clone History</h2>

            <p class="text-muted mb-0">
                Track original products and their cloned products
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('products.dashboard') }}"
                class="btn btn-dark"
            >
                📊 Dashboard
            </a>

            <a
                href="{{ route('products.index') }}"
                class="btn btn-primary"
            >
                Product List
            </a>

        </div>

    </div>


    <!-- Filters -->

    <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('products.clone-history') }}"
            >

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search Clone / Original Product
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search product name..."
                            value="{{ $search }}"
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ $fromDate }}"
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ $toDate }}"
                        >

                    </div>


                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Filter History
                        </button>

                        <a
                            href="{{ route('products.clone-history') }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- Clone History Table -->

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>Clone ID</th>

                            <th>Original Product</th>

                            <th>Cloned Product</th>

                            <th>Original ID</th>

                            <th>Clone Date</th>

                            <th>Clone Status</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($cloneHistory as $clone)

                        <tr>

                            <td>
                                #{{ $clone->id }}
                            </td>


                            <td>

                                @if($clone->originalProduct)

                                    <strong>
                                        {{ $clone->originalProduct->name }}
                                    </strong>

                                @else

                                    <span class="text-danger">
                                        Original Product Deleted
                                    </span>

                                @endif

                            </td>


                            <td>

                                {{ $clone->name }}

                            </td>


                            <td>

                                {{ $clone->cloned_from_id }}

                            </td>


                            <td>

                                {{ $clone->created_at->format('d M Y H:i:s') }}

                            </td>


                            <td>

                                <span class="badge bg-success">
                                    Clone Created
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                <h5>No clone history found</h5>

                                <p class="text-muted mb-0">
                                    Clone a product to create clone history.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($cloneHistory->hasPages())

            <div class="card-footer bg-white">

                {{ $cloneHistory->links('pagination::bootstrap-5') }}

            </div>

        @endif

    </div>

</div>

</body>

</html>