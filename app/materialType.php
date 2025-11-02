<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class materialType extends Model
{
    use SoftDeletes;

    protected $table = "material_types";
    public function types(){
        return $this->hasMany('App\materialJobType', 'type_id', 'material_id');
    }
}
