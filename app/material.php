<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class material extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'price'];

    public function jobtypes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\materialJobtype', 'material_id', 'id');
    }

    public function types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Type', 'material_types', 'material_id', 'type_id')
                    ->withTimestamps()
                    ->withPivot('deleted_at')
                    ->wherePivot('deleted_at', null);
    }

    public function implants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\implant', 'material_implants', 'material_id', 'implant_id')
                    ->withTimestamps()
                    ->withPivot(['compatibility_level', 'notes', 'is_active', 'deleted_at'])
                    ->wherePivot('deleted_at', null)
                    ->wherePivot('is_active', true);
    }

    public function allImplants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\implant', 'material_implants', 'material_id', 'implant_id')
                    ->withTimestamps()
                    ->withPivot(['compatibility_level', 'notes', 'is_active', 'deleted_at'])
                    ->wherePivot('deleted_at', null);
    }

}
