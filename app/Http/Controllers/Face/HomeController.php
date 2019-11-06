<?php

namespace App\Http\Controllers\Face;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProjectKeyCoords;
use App\Project;
use App\ProjectKey;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        return view('face');
    }


    public function getCoordinates(Request $request) {
        $activeProject = Project::getActive();

        $projectKeys = $activeProject->projectKeys();

        if ($request->id) {
            $projectKeys = $projectKeys->where('id', $request->id);
        }

        $projectKeys = $projectKeys->with('users')->get();

        $users = $projectKeys->reduce(function (Collection $carry, ProjectKey $projectKey) {
            return $carry->merge($projectKey->users->each(function (User $user) use ($projectKey) {
                $user->setRelation('projectKey', $projectKey);
            }));
        }, collect())->unique();

        return UserProjectKeyCoords::collection($users);
    }

    public function mapView() {
        return view('map');
    }
}
