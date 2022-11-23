<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class ProjectTechnology extends Model implements Sortable
{
    use HasPosition;

    protected $fillable = [
        'published',
        'title',
        'position',
        'project_id'
    ];

}
