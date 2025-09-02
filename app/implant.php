<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class implant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function materials(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\material', 'material_implants', 'implant_id', 'material_id')
                    ->withTimestamps()
                    ->withPivot(['compatibility_level', 'notes', 'is_active', 'deleted_at'])
                    ->wherePivot('deleted_at', null)
                    ->wherePivot('is_active', true);
    }

}
