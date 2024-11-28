<?php

namespace App\Providers;

use App\Models\Video;
use App\Observers\VideoObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Video::observe(VideoObserver::class);
    }
}
