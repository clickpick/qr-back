<?php

namespace App\Http\Controllers;

use App\Events\ProjectKeyActivated;
use App\Http\Requests\ActivateProjectKeyRequest;
use App\Http\Requests\AddFundsRequest;
use App\Http\Resources\ProjectFactResource;
use App\Http\Resources\ProjectKeyResource;
use App\Http\Resources\ProjectKeyTokenResource;
use App\Http\Resources\ProjectResource;
use App\Project;
use App\ProjectKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function getActive() {
        $project = Project::whereIsActive(true)->firstOrFail();

        $project->load('projectFacts');

        return new ProjectResource($project);
    }

    public function getUserProjectKey(Project $project) {
        $user = Auth::user();

        return new ProjectKeyTokenResource($user->getProjectKeyForProject($project));
    }

    public function activateProjectKey(ActivateProjectKeyRequest $request, Project $project) {
        $projectKeyIdToActivate = DB::table('project_key_user')
            ->where('project_keys.project_id', $project->id)
            ->where('project_key_user.token', $request->token)
            ->select(['project_keys.id as project_key_id', 'project_key_user.project_key_id', 'project_key_user.user_id'])
            ->join('project_keys', 'project_key_user.project_key_id', '=', 'project_keys.id')
            ->first();

        if (!$projectKeyIdToActivate) {
            abort(404);
        }

        if ($project->is_finished) {
            abort(403);
        }

        $user = Auth::user();

        if ($projectKeyIdToActivate->user_id === $user->id) {
            abort(403);
        }

        $userActivatedKeys = $user->activatedProjectKeys;

        $projectKeyIdToActivate = $projectKeyIdToActivate->project_key_id;

        $projectKey = ProjectKey::find($projectKeyIdToActivate);

        if ($user->activatedProjectKeys()->where('project_key_id', $projectKey->id)->exists()) {

            $userHasCheat = $user->hasAvailableNotFiredCheatForProject($project);
            $projectKey->setAttribute('hasCheat', $userHasCheat);

            return response(['data' => new ProjectKeyResource($projectKey)], 422);
        }

        $user->addProjectKey($projectKey);

        $project = $projectKey->project;
        $project->refresh();

        $projectKey->setAttribute('is_last', $project->is_finished);

        return new ProjectKeyResource($projectKey);
    }

    public function getUserActivatedProjectKeys(Project $project) {
        $user = Auth::user();

        $activatedKeys = $user->getActivatedProjectKeys($project);

        return ProjectKeyResource::collection($activatedKeys);
    }

    public function getFacts(Project $project) {
        $facts = $project->projectFacts;

        return ProjectFactResource::collection($facts);
    }

    /**
     * TODO: remove after connecting pay-to-service, for demonstration only
     *
     * @param AddFundsRequest $request
     * @param Project $project
     *
     * @return ProjectResource
     */
    public function addFunds(AddFundsRequest $request, Project $project) {
        $project->addFunds($request->value);

        return new ProjectResource($project);
    }
}
