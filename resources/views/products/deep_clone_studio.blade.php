<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deep Multi-Level Relational Clone Studio - Laravel Cloner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
        }

        .studio-container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 30px 15px 40px;
        }

        .studio-header {
            background: linear-gradient(135deg, #064e3b 0%, #047857 60%, #059669 100%);
            border-radius: 20px;
            padding: 28px;
            color: #ffffff;
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(4, 120, 87, 0.18);
        }

        .card-custom {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 25px;
        }

        .relation-node {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .relation-subnode {
            background: #ecfdf5;
            border-left: 3px solid #34d399;
            border-radius: 6px;
            padding: 6px 10px;
            margin-left: 20px;
            margin-top: 6px;
            font-size: 12px;
        }

        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <div class="studio-container">

        {{-- HEADER --}}
        <div class="studio-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-white">🌿 Deep Multi-Level Relational Clone Studio & Smart Field Replacer</h2>
                <p class="mb-0 text-light opacity-90">Deep duplicate products with nested variants & attributes using automated field replacer rules</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('products.dashboard') }}" class="btn btn-outline-light btn-sm">
                    📊 Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-sm">
                    📦 Products Catalog
                </a>
                <a href="{{ route('products.blueprints') }}" class="btn btn-light btn-sm fw-bold text-success">
                    🧬 Blueprints & Rollback →
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">
            {{-- LEFT PANEL: SMART FIELD REPLACER CONFIGURATOR & DEEP CLONE ACTION --}}
            <div class="col-lg-7">
                <div class="card-custom p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">
                        🛠️ Smart Field Replacer & Multi-Level Configurator
                    </h5>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Select Target Product to Deep Clone</label>
                        <select class="form-select form-select-lg" id="productSelector" onchange="updateDeepTree()">
                            @foreach($products as $p)
                            <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-sku="{{ $p->sku ?? 'SKU-'.$p->id }}" data-price="{{ $p->price }}" data-variants="{{ $p->variants->count() }}">
                                #{{ $p->id }} - {{ $p->name }} (${{ number_format($p->price, 2) }}) [{{ $p->variants->count() }} Variants]
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <form action="" method="POST" id="deepCloneForm">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name Prefix Rule</label>
                                <input type="text" class="form-control" name="prefix" id="prefixInput" value="Copy of " oninput="updatePreview()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name Suffix Rule</label>
                                <input type="text" class="form-control" name="suffix" id="suffixInput" value="[v2]" oninput="updatePreview()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">SKU Auto-Prefix</label>
                                <input type="text" class="form-control" name="sku_prefix" id="skuPrefixInput" value="CLONE-" oninput="updatePreview()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price Adjustment Multiplier</label>
                                <select class="form-select" name="price_multiplier" id="priceMultSelect" onchange="updatePreview()">
                                    <option value="1.0">Same Price (100%)</option>
                                    <option value="1.10" selected>+10% Markup (110%)</option>
                                    <option value="1.25">+25% Markup (125%)</option>
                                    <option value="0.90">-10% Discount (90%)</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check form-switch bg-light p-3 rounded-3 border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="reset_stock" value="1" id="resetStockSwitch">
                                    <label class="form-check-label fw-semibold text-dark" for="resetStockSwitch">
                                        Reset Inventory Stock to 0 (Draft Catalog Copy)
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 bg-light p-3 rounded-3 border border-success">
                                <h6 class="fw-bold mb-1 text-success"><i class="bi bi-eye me-1"></i> Cloned Preview Output:</h6>
                                <div class="small">
                                    <div><strong>Cloned Name:</strong> <span id="prevName" class="fw-bold text-dark">Copy of Pro Gaming Keyboard [v2]</span></div>
                                    <div><strong>Cloned SKU:</strong> <span id="prevSku" class="font-monospace text-primary">CLONE-SKU-102</span></div>
                                    <div><strong>Adjusted Price:</strong> <span id="prevPrice" class="fw-bold text-success">$163.90</span></div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 text-end">
                                <button type="submit" class="btn btn-success btn-lg px-4 py-2 rounded-3 shadow">
                                    <i class="bi bi-lightning-fill me-1"></i> Execute Deep Multi-Level Clone
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT PANEL: NESTED RELATIONS GRAPH INSPECTOR --}}
            <div class="col-lg-5">
                <div class="card-custom p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">
                        🌳 Nested Relations Tree Visualizer
                    </h5>

                    <div id="treeContainer">
                        <div class="relation-node">
                            <i class="bi bi-box-seam me-2 text-success"></i> <strong>Level 1: Main Product Record</strong>
                            <div class="small text-dark fw-bold" id="treeMainName">Pro Gaming Mechanical Keyboard</div>

                            <div class="relation-subnode">
                                <i class="bi bi-diagram-2 me-1 text-teal"></i> Level 2: <strong>Product Variants</strong> (Nested Child Records)
                                <div class="mt-1 ps-2 border-start border-2 border-success">
                                    <div class="text-secondary">✦ Color: Black / Size: Standard (SKU: VAR-8812)</div>
                                    <div class="text-secondary ms-3">↳ Level 3: <strong>Attribute:</strong> Switch = Cherry MX Red</div>
                                    <div class="text-secondary ms-3">↳ Level 3: <strong>Attribute:</strong> RGB = 16.8M Colors</div>
                                    <div class="text-secondary">✦ Color: White / Size: Tenkeyless (SKU: VAR-8813)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mt-4 mb-2 text-dark">
                        📋 Recent Deep Clones History
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Cloned Product</th>
                                    <th>Relations</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $p)
                                    @if($p->cloned_from_id)
                                    <tr>
                                        <td class="fw-semibold text-truncate" style="max-width: 150px;">{{ $p->name }}</td>
                                        <td><span class="badge bg-success">{{ $p->variants->count() }} Variants</span></td>
                                        <td class="fw-bold text-success">${{ number_format($p->price, 2) }}</td>
                                    </tr>
                                    @endif
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted small">No deep clones executed yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function updateDeepTree() {
            const select = document.getElementById('productSelector');
            if (!select || !select.options.length) return;
            const option = select.options[select.selectedIndex];
            const id = option.value;
            const name = option.getAttribute('data-name');

            document.getElementById('deepCloneForm').action = "{{ url('/products/deep-clone') }}/" + id;
            document.getElementById('treeMainName').innerText = name;
            updatePreview();
        }

        function updatePreview() {
            const select = document.getElementById('productSelector');
            if (!select || !select.options.length) return;
            const option = select.options[select.selectedIndex];
            const name = option.getAttribute('data-name') || 'Product';
            const price = parseFloat(option.getAttribute('data-price')) || 100;
            const sku = option.getAttribute('data-sku') || 'SKU-001';

            const prefix = document.getElementById('prefixInput').value;
            const suffix = document.getElementById('suffixInput').value;
            const skuPrefix = document.getElementById('skuPrefixInput').value;
            const mult = parseFloat(document.getElementById('priceMultSelect').value) || 1.0;

            document.getElementById('prevName').innerText = (prefix + name + ' ' + suffix).trim();
            document.getElementById('prevSku').innerText = skuPrefix + sku;
            document.getElementById('prevPrice').innerText = '$' + (price * mult).toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateDeepTree();
        });
    </script>
</body>

</html>
