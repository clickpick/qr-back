<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetNotificationsAreEnabledRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function me() {
        return new UserResource(Auth::user());
    }

    public function setNotificationsAreEnabled(SetNotificationsAreEnabledRequest $request) {
        $user = Auth::user();

        $user->notifications_are_enabled = $request->enabled;
        $user->save();

        return new UserResource($user);
    }
}
