<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Model;

class Cv extends Model
{
    use HasFiles;

    public $filesParams = ['cv'];

    protected $fillable = [
        'title',
    ];

    protected $indexOptions = ['publish' => false];

    public static function getCv(): ?string
    {
        $cv = Cv::first();

        return $cv ? $cv->file('cv') : null;
    }

}
