<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use App\Models\CloneBlueprint;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $p1 = Product::create([
            'name' => 'Pro Mechanical Gaming Keyboard',
            'sku' => 'SKU-GAM-101',
            'category' => 'Electronics',
            'price' => 149.99,
            'stock' => 40,
            'status' => 'active',
            'description' => 'RGB mechanical gaming keyboard with Cherry MX switches',
        ]);

        $v1 = ProductVariant::create([
            'product_id' => $p1->id,
            'sku' => 'VAR-GAM-RED',
            'color' => 'Black',
            'size' => 'Standard',
            'price_modifier' => 0,
            'stock' => 25,
        ]);

        ProductAttribute::create([
            'product_variant_id' => $v1->id,
            'attribute_name' => 'Switch Type',
            'attribute_value' => 'Cherry MX Red',
        ]);

        ProductAttribute::create([
            'product_variant_id' => $v1->id,
            'attribute_name' => 'Backlight',
            'attribute_value' => 'RGB 16.8M',
        ]);

        $p2 = Product::create([
            'name' => 'Ultra Soft Cotton Hoodie',
            'sku' => 'SKU-CLO-202',
            'category' => 'Clothing',
            'price' => 79.50,
            'stock' => 85,
            'status' => 'active',
            'description' => '100% organic cotton pullover hoodie',
        ]);

        ProductVariant::create([
            'product_id' => $p2->id,
            'sku' => 'VAR-HD-BL-M',
            'color' => 'Navy Blue',
            'size' => 'M',
            'price_modifier' => 5.00,
            'stock' => 30,
        ]);

        CloneBlueprint::create([
            'name' => 'Full Catalog with Inventory Preset',
            'preset_key' => 'preset_full_catalog',
            'clone_relations' => ['variants', 'attributes'],
            'prefix_rule' => 'Copy of ',
            'price_modifier_percentage' => 10.0,
            'reset_stock_to_zero' => false,
            'status_override' => 'draft',
            'is_default' => true,
        ]);

        CloneBlueprint::create([
            'name' => 'Draft Status Clone Preset',
            'preset_key' => 'preset_draft_clone',
            'clone_relations' => ['variants'],
            'prefix_rule' => 'Draft - ',
            'price_modifier_percentage' => 0.0,
            'reset_stock_to_zero' => true,
            'status_override' => 'draft',
            'is_default' => false,
        ]);
    }
}
