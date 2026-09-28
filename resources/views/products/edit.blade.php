<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Product</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f6f9;
        }

        .container-main {
            max-width: 650px;
            margin: 50px auto;
        }

        .card {
            border: none;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

<div class="container-main">

    <div class="card">

        <div class="card-header bg-warning">

            <h4 class="mb-0">
                Edit Product
            </h4>

        </div>


        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('products.update', $product->id) }}"
            >

                @csrf


                <div class="mb-3">

                    <label class="form-label">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $product->name) }}"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Price
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        min="0"
                        step="0.01"
                        value="{{ old('price', $product->price) }}"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="5"
                    >{{ old('description', $product->description) }}</textarea>

                </div>


                @if($product->originalProduct)

                    <div class="alert alert-info">

                        <strong>
                            Cloned From:
                        </strong>

                        {{ $product->originalProduct->name }}

                    </div>

                @else

                    <div class="alert alert-success">

                        This is an original product.

                    </div>

                @endif


                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Update Product
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-secondary"
                    >
                        Back
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>

</html>