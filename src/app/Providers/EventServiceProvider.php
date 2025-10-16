<?php

namespace MHMartinez\ImpersonateUser\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use MHMartinez\ImpersonateUser\app\Events\UserFinishedImpersonating;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (class_exists('\MHMartinez\TwoFactorAuth\app\Listeners\ReLoginUserListener', false)) {
            Event::listen(
                UserFinishedImpersonating::class,
                '\MHMartinez\TwoFactorAuth\app\Listeners\ReLoginUserListener',
            );
        }
    }
}
