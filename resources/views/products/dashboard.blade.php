<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Product Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f6f9;
        }

        .container-main {
            max-width: 1300px;
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

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>

            <h2>
                📊 Product Dashboard
            </h2>

            <p class="text-muted mb-0">
                Product and cloning analytics
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


    <!-- Main Statistics -->

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

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Clone Rate
                    </h6>

                    <div class="stat-number text-info">
                        {{ $cloneRate }}%
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Price Statistics -->

    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Product Value
                    </h6>

                    <div class="stat-number">
                        ₹{{ number_format((float) $totalPrice, 2) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Average Price
                    </h6>

                    <div class="stat-number">
                        ₹{{ number_format((float) $averagePrice, 2) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Minimum Price
                    </h6>

                    <div class="stat-number">
                        ₹{{ number_format((float) $minimumPrice, 2) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Maximum Price
                    </h6>

                    <div class="stat-number">
                        ₹{{ number_format((float) $maximumPrice, 2) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Price Range -->

    <div class="card section-card mb-4">

        <div class="card-header bg-dark text-white">

            <strong>
                💰 Products by Price Range
            </strong>

        </div>


        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-4">

                    <h5>
                        Below ₹5,000
                    </h5>

                    <h2 class="text-success">
                        {{ $lowPriceProducts }}
                    </h2>

                </div>


                <div class="col-md-4">

                    <h5>
                        ₹5,000 – ₹20,000
                    </h5>

                    <h2 class="text-warning">
                        {{ $mediumPriceProducts }}
                    </h2>

                </div>


                <div class="col-md-4">

                    <h5>
                        Above ₹20,000
                    </h5>

                    <h2 class="text-danger">
                        {{ $highPriceProducts }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4 mb-4">

        <!-- Top Expensive -->

        <div class="col-md-6">

            <div class="card section-card">

                <div class="card-header bg-danger text-white">

                    <strong>
                        💎 Top 5 Expensive Products
                    </strong>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Product
                                    </th>

                                    <th>
                                        Price
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse($topExpensiveProducts as $product)

                                <tr>

                                    <td>
                                        {{ $product->name }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $product->price, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="2"
                                        class="text-center text-muted"
                                    >
                                        No products found.
                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <!-- Recent Products -->

        <div class="col-md-6">

            <div class="card section-card">

                <div class="card-header bg-success text-white">

                    <strong>
                        🆕 Recent Products
                    </strong>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Product
                                    </th>

                                    <th>
                                        Price
                                    </th>

                                    <th>
                                        Created
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse($recentProducts as $product)

                                <tr>

                                    <td>
                                        {{ $product->name }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $product->price, 2) }}
                                    </td>

                                    <td>
                                        {{ $product->created_at->format('d M Y') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center text-muted"
                                    >
                                        No products found.
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


    <div class="row g-4">

        <!-- Most Cloned -->

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

                                    <th>
                                        Product
                                    </th>

                                    <th>
                                        Clone Count
                                    </th>

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


        <!-- Recent Clones -->

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

                                    <th>
                                        Original
                                    </th>

                                    <th>
                                        Clone
                                    </th>

                                    <th>
                                        Date
                                    </th>

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