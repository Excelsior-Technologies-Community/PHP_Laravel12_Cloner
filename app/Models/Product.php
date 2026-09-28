<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bkwld\Cloner\Cloneable;

class Product extends Model
{
    use Cloneable;

    protected $fillable = [
        'name',
        'price',
        'description',
        'cloned_from_id',
    ];

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
}