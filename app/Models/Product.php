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
        'description'
    ];
}