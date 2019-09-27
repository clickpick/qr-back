<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function handleProviderCallback()
    {
        $incomeUser = Socialite::driver('vkontakte')->stateless()->user();

        $user = User::getByVkId($incomeUser->id);

        if ($user->isAdmin()) {
            Auth::login($user);
            return redirect('admin');
        }

        abort(403);
    }
}
