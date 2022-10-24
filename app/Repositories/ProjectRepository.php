<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Project;

class ProjectRepository extends ModuleRepository
{
    use HandleBlocks, HandleSlugs, HandleMedias;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function allProjects()
    {
        $news = $this->model->published()->orderBy('position')->latest();

        if($search = request('search')) {
            $news->where('title', 'like', '%' . $search . '%');
        }

        return $news->get();
    }

//    public function featuredProject()
//    {
//        return $this->model
//            ->published()
//            ->orderBy('position')
//            ->where('featured', 1)
//            ->latest()
//            ->take(3)
//            ->get();
//    }
//
//    public function filteredHomeProject($news)
//    {
//        return $this->model
//            ->published()
//            ->orderBy('position')
//            ->whereNotIn('id', $news->pluck('id'))
//            ->latest()->take(4)
//            ->get();
//    }
//
//    public function threeLatest($news = null)
//    {
//        $newsId = $news ? $news->id : null;
//
//        return $this->model
//            ->where('id', '!=', $newsId)
//            ->orderBy('position')
//            ->published()
//            ->latest()
//            ->take(3)
//            ->get();
//    }
}
