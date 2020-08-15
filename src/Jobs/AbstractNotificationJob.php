<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019, 2020  Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Notifications\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Seat\Services\Helpers\AnalyticsContainer;
use Seat\Services\Jobs\Analytics;

/**
 * Class AbstractNotificationJob.
 *
 * @package Seat\Notifications\Jobs
 */
abstract class AbstractNotificationJob extends Notification implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * When a job fails, grab some information and send a
     * GA event about the exception. The Analytics job
     * does the work of checking if analytics is disabled
     * or not, so we don't have to care about that here.
     *
     * On top of that, we also increment the error rate
     * limiter. This is checked as part of the preflight
     * checks when API calls are made.
     *
     * @param \Exception $exception
     * @throws \Exception
     */
    public function failed(Exception $exception)
    {
        // Analytics. Report only the Exception class and message.
        dispatch((new Analytics((new AnalyticsContainer)
            ->set('type', 'exception')
            ->set('exd', get_class($exception) . ':' . $exception->getMessage())
            ->set('exf', 1))))->onQueue('default');

        // Rethrow the original exception for Horizon
        throw $exception;
    }

    /**
     * Assign this job a tag so that Horizon can categorize and allow
     * for specific tags to be monitored.
     *
     * @return array
     */
    public function tags(): array
    {
        if (property_exists($this, 'tags'))
            return array_merge(['notifications'], $this->tags);

        return ['notifications', 'other'];
    }
}
