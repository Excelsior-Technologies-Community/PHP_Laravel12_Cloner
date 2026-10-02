<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloneAuditLog extends Model
{
    protected $fillable = [
        'batch_id',
        'original_product_id',
        'cloned_product_id',
        'blueprint_name',
        'relations_cloned_count',
    ];

    public function originalProduct()
    {
        return $this->belongsTo(Product::class, 'original_product_id');
    }

    public function clonedProduct()
    {
        return $this->belongsTo(Product::class, 'cloned_product_id');
    }
}
