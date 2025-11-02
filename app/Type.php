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
    public function materials(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\material', 'material_types', 'type_id', 'material_id')
                    ->withTimestamps()
                    ->withPivot('deleted_at')
                    ->wherePivot('deleted_at', null);
    }
    public function material(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\material', 'material_id', 'id');
    }
    public function jobs()
    {
        return $this->hasMany('App\job', 'type_id', 'id');
    }
}
