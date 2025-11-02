<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class material extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'price', 'default_type_id', 'type_selection_stage'];

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

    public function defaultType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Type', 'default_type_id', 'id');
    }

    public static function getDefaultType()
    {
        $material = static::whereNotNull('default_type_id')->first();
        return $material ? $material->default_type_id : null;
    }

}
