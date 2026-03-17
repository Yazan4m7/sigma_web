<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use JustSteveKing\Laravel\FeatureFlags\Concerns\HasFeatures;
class User extends Authenticatable
{

    use SoftDeletes;
    use HasFeatures;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function permissions(){
        return $this->hasMany('App\UserPermission', 'user_id', 'id');
    }
    //helper
    public function fullName(){
        return $this->first_name .' ' . $this->last_name ;
    }

    public function getAvatarPathAttribute(): string
    {
        $default = 'assets/images/avatars/default.png';
        if (!empty($this->img)) {
            if (preg_match('~^https?://~i', $this->img)) {
                return $this->img;
            }
            $path = ltrim($this->img, '/');
            if (strpos($path, 'public/') === 0) {
                $path = substr($path, 7);
            }
            if (file_exists(public_path($path))) {
                return $path;
            }
        }
        if ($this->has_photo) {
            $path = 'assets/images/avatars/user_' . $this->id . '.webp';
            if (file_exists(public_path($path))) {
                return $path;
            }
        }
        return $default;
    }

}
