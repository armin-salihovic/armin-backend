<?php

namespace App\Observers;

use App\Models\Cache;
use App\Models\Cv;

class CvObserver
{
    /**
     * Handle the Cv "updated" event.
     *
     * @param  \App\Models\Cv  $cv
     * @return void
     */
    public function updated(Cv $cv)
    {
        $cache = Cache::first();
        $cache->update([
            'cache' => uniqid(),
        ]);
    }
}
