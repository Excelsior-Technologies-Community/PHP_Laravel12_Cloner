<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add SKU, category, stock, status to products table if missing
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(50)->after('price');
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->string('status')->default('active')->after('stock');
            }
        });

        // 2. Product Variants table for deep nested cloning
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price_modifier', 10, 2)->default(0.00);
            $table->integer('stock')->default(25);
            $table->timestamps();
        });

        // 3. Product Attributes table (nested level 3)
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->string('attribute_name');
            $table->string('attribute_value');
            $table->timestamps();
        });

        // 4. Clone Blueprint Presets Table
        Schema::create('clone_blueprints', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('preset_key')->unique();
            $table->json('clone_relations')->nullable();
            $table->string('prefix_rule')->default('Copy of ');
            $table->string('suffix_rule')->nullable();
            $table->decimal('price_modifier_percentage', 5, 2)->default(0.00);
            $table->boolean('reset_stock_to_zero')->default(false);
            $table->string('status_override')->default('draft');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 5. Clone Audit Logs & Batch Tracking Table
        Schema::create('clone_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->index();
            $table->foreignId('original_product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('cloned_product_id')->constrained('products')->onDelete('cascade');
            $table->string('blueprint_name')->nullable();
            $table->integer('relations_cloned_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clone_audit_logs');
        Schema::dropIfExists('clone_blueprints');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_variants');
    }
};
