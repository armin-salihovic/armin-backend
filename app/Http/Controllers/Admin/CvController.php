<?php

namespace App\Http\Controllers\Admin;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use App\Models\Cv;

class CvController extends BaseModuleController
{
    protected $moduleName = 'cvs';

    protected $indexOptions = [
        'permalink' => false,
    ];

    public function cv()
    {
        abort_unless($cv = CV::first(), 500, 'CV record missing');

        return view('admin.cvs.form',  $this->form($cv->id));
    }
}
