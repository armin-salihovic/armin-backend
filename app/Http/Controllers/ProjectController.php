<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectResourceBasic;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    function index()
    {
        return ProjectResourceBasic::collection($this->repository->allProjects());
    }

    function show($slug)
    {
        $project = $this->repository->forSlug($slug);

        if($project === null) return response()->json([], 404);

        return new ProjectResource($project);
    }
}
