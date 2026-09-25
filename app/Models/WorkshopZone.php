<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color',
        'pos_x',
        'pos_y',
        'width',
        'height',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'pos_x'      => 'float',
        'pos_y'      => 'float',
        'width'      => 'float',
        'height'     => 'float',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Stasiun-stasiun yang berada di zona ini
     */
    public function nodes()
    {
        return $this->hasMany(WorkshopNode::class, 'zone', 'code');
    }
}
