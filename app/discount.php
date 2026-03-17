<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class discount extends Model
{
    use SoftDeletes;

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
}
