<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bkwld\Cloner\Cloneable;

class ProductAttribute extends Model
{
    use Cloneable;

    protected $fillable = [
        'product_variant_id',
        'attribute_name',
        'attribute_value',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
