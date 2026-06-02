<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Support\UserPermissionsCache;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo =  RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }

    protected function authenticated(Request $request, $user)
    {
        if ((int) $user->is_admin === 1) {
            return redirect()->route('homeScreen');
        }

        $permissions = UserPermissionsCache::get((int) $user->id);
        $request->attributes->set('user_permissions', $permissions);

        if ($permissions->contains('permission_id', 123)) {
            return redirect()->route('homeScreen');
        }

        return redirect('/operations-dashboard');
    }
}
