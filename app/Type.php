<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'material_id', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    // Scope for enabled types only
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function material()
    {
        return $this->belongsTo('App\material', 'material_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany('App\job', 'type_id', 'id');
    }
}