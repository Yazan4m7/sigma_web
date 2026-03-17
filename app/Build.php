<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Build extends Model
{
    use SoftDeletes;
//    protected $appends = ['balance'];
    public function cases()
    {
        return $this->hasManyThrough(
            sCase::class, // related model
            Job::class,       // through model
            'printing_build_id',       // Foreign key on jobs table
            'id',             // Foreign key on cases table (referenced by case_id)
            'id',             // Local key on builds table
            'case_id'         // Local key on jobs table
        )->distinct(); // 👈 to avoid duplicate cases
    }
    public function jobs()
    {
        return $this->hasMany(job::class, 'printing_build_id');
    }

    public function millingJobs()
    {
        return $this->hasMany(job::class, 'milling_build_id');
    }

    public function sinteringJobs()
    {
        return $this->hasMany(job::class, 'sintering_build_id');
    }

    public function pressingJobs()
    {
        return $this->hasMany(job::class, 'pressing_build_id');
    }

    public function millingBuild()
    {
        return $this->belongsTo(Build::class, 'milling_build_id');
    }

    public function printingBuild()
    {
        return $this->belongsTo(Build::class, 'printing_build_id');
    }

    public function device()
    {
        return $this->belongsTo('App\device', 'device_id', 'id');
    }

    public function printer()
    {
        return $this->belongsTo('App\device', 'device_used', 'id');
    }

    public function deviceUsed()
    {
        return $this->belongsTo('App\device', 'device_used', 'id');
    }

}
