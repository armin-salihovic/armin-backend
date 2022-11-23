<?php

namespace App\Repositories;


use A17\Twill\Repositories\ModuleRepository;
use App\Models\ProjectTechnology;

class ProjectTechnologyRepository extends ModuleRepository
{
    

    public function __construct(ProjectTechnology $model)
    {
        $this->model = $model;
    }
}
