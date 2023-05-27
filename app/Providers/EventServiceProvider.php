<?php

namespace App\Providers;

use A17\Twill\Models\Translations\SettingTranslation;
use App\Jobs\RefreshLQIP;
use App\Models\Cv;
use App\Observers\CvObserver;
use App\Observers\SettingTranslationObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('cms-module.saved', function () {
            RefreshLQIP::dispatch();
        });

        SettingTranslation::observe(SettingTranslationObserver::class);
        Cv::observe(CvObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
