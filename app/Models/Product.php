<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bkwld\Cloner\Cloneable;

class Product extends Model
{
    use Cloneable;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'price',
        'stock',
        'status',
        'description',
        'cloned_from_id',
    ];

    /**
     * Relations that should be cloned automatically with this product.
     */
    protected $cloneable_relations = [
        'variants',
    ];

    /**
     * Product variants relation for deep multi-level cloning.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Product from which this product was cloned.
     */
    public function originalProduct()
    {
        return $this->belongsTo(Product::class, 'cloned_from_id');
    }

    /**
     * Products cloned from this product.
     */
    public function clones()
    {
        return $this->hasMany(Product::class, 'cloned_from_id');
    }

    /**
     * Audit logs as original product.
     */
    public function auditLogs()
    {
        return $this->hasMany(CloneAuditLog::class, 'original_product_id');
    }
}