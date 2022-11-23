<?php

namespace App\Http\Controllers\Admin;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

class ProjectTechnologyController extends BaseModuleController
{
    protected $moduleName = 'projectTechnologies';

    protected $indexOptions = [
        'permalink' => false,
    ];
}
