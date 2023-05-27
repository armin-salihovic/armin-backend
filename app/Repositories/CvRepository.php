<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Cv;

class CvRepository extends ModuleRepository
{
    use HandleFiles;

    public function __construct(Cv $model)
    {
        $this->model = $model;
    }
}
