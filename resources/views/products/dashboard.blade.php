<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Clone Dashboard</title>

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

        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .stat-number {
            font-size: 30px;
            font-weight: bold;
        }

        .section-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

<div class="container-main">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>📊 Product Clone Dashboard</h2>

            <p class="text-muted">
                Product cloning statistics and recent activity
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('products.index') }}"
                class="btn btn-primary"
            >
                Product List
            </a>

            <a
                href="{{ route('products.clone-history') }}"
                class="btn btn-info text-white"
            >
                Clone History
            </a>

        </div>

    </div>


    <!-- Statistics -->

    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Products
                    </h6>

                    <div class="stat-number">
                        {{ $totalProducts }}
                    </div>

                    <small class="text-muted">
                        All products
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Original Products
                    </h6>

                    <div class="stat-number text-success">
                        {{ $originalProducts }}
                    </div>

                    <small class="text-muted">
                        Original records
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Cloned Products
                    </h6>

                    <div class="stat-number text-primary">
                        {{ $clonedProducts }}
                    </div>

                    <small class="text-muted">
                        Products created by cloning
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Clone Operations
                    </h6>

                    <div class="stat-number text-info">
                        {{ $totalCloneOperations }}
                    </div>

                    <small class="text-muted">
                        Total clone records
                    </small>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        <!-- Most cloned products -->

        <div class="col-md-6">

            <div class="card section-card">

                <div class="card-header bg-dark text-white">

                    <strong>
                        🏆 Most Cloned Products
                    </strong>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>Product</th>

                                    <th>Clone Count</th>

                                </tr>

                            </thead>

                            <tbody>

                            @forelse($productsWithMostClones as $product)

                                <tr>

                                    <td>
                                        {{ $product->name }}
                                    </td>

                                    <td>

                                        <span class="badge bg-primary">

                                            {{ $product->clones_count }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="2"
                                        class="text-center text-muted py-4"
                                    >
                                        No clone activity yet.
                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <!-- Recent clone activity -->

        <div class="col-md-6">

            <div class="card section-card">

                <div class="card-header bg-primary text-white">

                    <strong>
                        🕒 Recent Clone Activity
                    </strong>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>Original</th>

                                    <th>Clone</th>

                                    <th>Date</th>

                                </tr>

                            </thead>

                            <tbody>

                            @forelse($recentClones as $clone)

                                <tr>

                                    <td>

                                        {{ $clone->originalProduct?->name ?? 'Deleted Product' }}

                                    </td>

                                    <td>

                                        {{ $clone->name }}

                                    </td>

                                    <td>

                                        {{ $clone->created_at->format('d M Y H:i') }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center text-muted py-4"
                                    >
                                        No clone activity yet.
                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>