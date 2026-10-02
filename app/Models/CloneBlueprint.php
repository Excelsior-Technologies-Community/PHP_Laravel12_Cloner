<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloneBlueprint extends Model
{
    protected $fillable = [
        'name',
        'preset_key',
        'clone_relations',
        'prefix_rule',
        'suffix_rule',
        'price_modifier_percentage',
        'reset_stock_to_zero',
        'status_override',
        'is_default',
    ];

    protected $casts = [
        'clone_relations' => 'array',
        'reset_stock_to_zero' => 'boolean',
        'is_default' => 'boolean',
        'price_modifier_percentage' => 'float',
    ];
}
