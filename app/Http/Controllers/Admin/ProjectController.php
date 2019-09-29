<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\Admin\ProjectResource;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::all();

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request) {
        $project = Project::make($request->validated());
        $project->save();

        if ($request->poster) {
            $project->addMedia($request->poster)->preservingOriginal()->toMediaCollection('poster');
        }

        if ($request->banner) {
            $project->addMedia($request->banner)->preservingOriginal()->toMediaCollection('banner');
        }

        $project = $project->refresh();

        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project) {
        $project->update($request->validated());

        if ($request->poster) {
            $project->addMedia($request->poster)->preservingOriginal()->toMediaCollection('poster');
        }

        if ($request->banner) {
            $project->addMedia($request->banner)->preservingOriginal()->toMediaCollection('banner');
        }

        $project = $project->refresh();

        return new ProjectResource($project);
    }

    public function destroy(Project $project) {
        $project->delete();

        abort(204);
    }

    public function makeActive(Project $project) {
        $project->activate();
        $project->refresh();

        return new ProjectResource($project);
    }
}
