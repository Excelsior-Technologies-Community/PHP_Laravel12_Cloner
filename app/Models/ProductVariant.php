<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bkwld\Cloner\Cloneable;

class ProductVariant extends Model
{
    use Cloneable;

    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'size',
        'price_modifier',
        'stock',
    ];

    /**
     * Relations that should be cloned automatically with this variant.
     */
    protected $cloneable_relations = [
        'attributes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
