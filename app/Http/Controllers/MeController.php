<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetNotificationsAreEnabledRequest;
use App\Http\Resources\UserResource;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function me() {

        $user = Auth::user();

        $activeProject = Project::whereIsActive(true)->first();

        if ($activeProject) {
            $activeUserKey = $user->getProjectKeyForProject($activeProject);
            $user->setRelation('activeProjectKey', $activeUserKey);

            $activatedKeys = $user->getActivatedProjectKeys($activeProject);
            $user->setRelation('activatedProjectKeys', $activatedKeys);
        }

        return new UserResource($user);
    }

    public function setNotificationsAreEnabled(SetNotificationsAreEnabledRequest $request) {
        $user = Auth::user();

        $user->notifications_are_enabled = $request->enabled;
        $user->save();

        return new UserResource($user);
    }
}
