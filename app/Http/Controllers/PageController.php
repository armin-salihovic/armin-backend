<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

//    function index()
//    {
//        return PageResourceBasic::collection($this->repository->allPages());
//    }

    function show($slug)
    {
        $project = $this->repository->forSlug($slug);

        if($project === null) return response(null,404);

        return new PageResource($project);
    }
}
