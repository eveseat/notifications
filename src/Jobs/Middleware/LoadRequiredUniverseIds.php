<?php

namespace Seat\Notifications\Jobs\Middleware;

use Closure;
use Illuminate\Notifications\SendQueuedNotifications;
use Seat\Eveapi\Jobs\Universe\Names as ResolveUniverseNames;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;

class LoadRequiredUniverseIds
{
    public function handle(object $job, Closure $next): void
    {
        if (
            $job instanceof SendQueuedNotifications
            && $job->notification instanceof ExposesRequiredUniverseIds
            && $job->notification->getRequiredUniverseIds()->isNotEmpty()
        ) {
            ResolveUniverseNames::dispatchSync($job->notification->getRequiredUniverseIds());
        }

        $next($job);
    }
}