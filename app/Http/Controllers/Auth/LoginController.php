<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\User;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function redirectToProvider() {
        return Socialite::driver('facebook')->redirect();
    }
    
    public function handleProviderCallback() {
        try{
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $ex) {
    return redirect('auth');
        }
        
        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser,true);
        return redirect('home');
    }
    
    public function findOrCreateUser($facebookUser) {
        $authUser = User::where('facebook_id', $facebookUser->id)->first();
        if($authUser){
            return $authUser;
        }
        
        return User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'facebook_id' => $facebookUser-id
        ]);
    }
}
