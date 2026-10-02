<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clone Blueprint Presets & Rollback Inspector - Laravel Cloner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
        }

        .blueprint-container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 30px 15px 40px;
        }

        .blueprint-header {
            background: linear-gradient(135deg, #311b92 0%, #4527a0 60%, #512da8 100%);
            border-radius: 20px;
            padding: 28px;
            color: #ffffff;
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(69, 39, 160, 0.18);
        }

        .card-custom {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 25px;
        }

        .blueprint-preset-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            background: #f8fafc;
            transition: transform 0.2s;
        }

        .blueprint-preset-card:hover {
            transform: translateY(-2px);
        }

        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <div class="blueprint-container">

        {{-- HEADER --}}
        <div class="blueprint-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-white">🧬 Clone Blueprint Presets & Rollback Inspector Studio</h2>
                <p class="mb-0 text-light opacity-90">Manage clone preset blueprints, audit cloning history & execute batch rollbacks</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('products.dashboard') }}" class="btn btn-outline-light btn-sm">
                    📊 Dashboard
                </a>
                <a href="{{ route('products.deep-clone-studio') }}" class="btn btn-outline-light btn-sm">
                    🌿 Deep Clone Studio
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-sm fw-bold text-primary">
                    📦 Products Catalog →
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
            {{-- LEFT PANEL: BLUEPRINT PRESET BUILDER --}}
            <div class="col-lg-6">
                <div class="card-custom p-4 h-100">
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">
                        ⚙️ Custom Clone Blueprint Preset Configurator
                    </h5>

                    <form action="{{ route('products.blueprints.save') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Blueprint Name</label>
                                <input type="text" class="form-control" name="name" value="Full Catalog with Inventory Preset" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Default Status</label>
                                <select class="form-select" name="status_override">
                                    <option value="draft" selected>Draft Status</option>
                                    <option value="active">Active Status</option>
                                    <option value="archived">Archived Status</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Auto Prefix Rule</label>
                                <input type="text" class="form-control" name="prefix_rule" value="Copy of ">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price Modifier (%)</label>
                                <input type="number" class="form-control" name="price_modifier_percentage" value="0.00" step="0.5">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Relations to Clone</label>
                                <div class="d-flex gap-3 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="clone_relations[]" value="variants" id="relVar" checked>
                                        <label class="form-check-label" for="relVar">Product Variants</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="clone_relations[]" value="attributes" id="relAttr" checked>
                                        <label class="form-check-label" for="relAttr">Variant Attributes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="clone_relations[]" value="images" id="relImg" checked>
                                        <label class="form-check-label" for="relImg">Gallery Images</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check form-switch bg-light p-3 rounded-3 border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="reset_stock_to_zero" value="1" id="resetStockRule">
                                    <label class="form-check-label fw-semibold text-dark" for="resetStockRule">
                                        Reset Inventory Stock to Zero in Cloned Copies
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow">
                                    <i class="bi bi-save me-1"></i> Save Clone Blueprint Preset
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- EXISTING BLUEPRINTS PRESETS LIST --}}
                    <div class="mt-4 border-top pt-3">
                        <h6 class="fw-bold text-dark mb-3">📋 Saved Blueprint Templates:</h6>

                        <div class="row g-2">
                            @forelse($blueprints as $bp)
                            <div class="col-md-6">
                                <div class="blueprint-preset-card">
                                    <div class="fw-bold text-primary">{{ $bp->name }}</div>
                                    <small class="text-muted d-block">Prefix: '{{ $bp->prefix_rule }}' | Status: {{ strtoupper($bp->status_override) }}</small>
                                    <span class="badge bg-primary-subtle text-primary mt-2">
                                        Relations: {{ is_array($bp->clone_relations) ? implode(', ', $bp->clone_relations) : 'All' }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-muted small">No blueprint presets saved yet. Save one above.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL: CLONE AUDIT LOGS & BATCH ROLLBACK INSPECTOR --}}
            <div class="col-lg-6">
                <div class="card-custom p-4 h-100">
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">
                        🔄 Clone Audit Log & Batch Rollback Inspector
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Batch ID</th>
                                    <th>Original Product</th>
                                    <th>Cloned Result</th>
                                    <th>Relations</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                <tr>
                                    <td>
                                        <span class="font-monospace small fw-bold text-primary">{{ $log->batch_id }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $log->originalProduct->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">{{ $log->clonedProduct->name ?? 'Deleted' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $log->relations_cloned_count }} cloned</span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('products.rollback-batch', $log->batch_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Rollback this cloned batch?')">
                                                <i class="bi bi-arrow-counterclockwise"></i> Revert Batch
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No cloning audit logs recorded yet. Execute a deep clone to view batch logs.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $auditLogs->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
