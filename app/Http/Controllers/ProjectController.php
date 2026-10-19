<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectResourceBasic;
use App\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ProjectResourceBasic::collection($this->repository->allProjects());
    }

    public function show($slug)
    {
        $project = $this->repository->forSlug($slug);

        if ($project === null || ! $project->hasActiveTranslation(request()->attributes->get('locale', 'en'))) {
            return response()->json([], 404);
        }

        return new ProjectResource($project);
    }
}
