<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

use Illuminate\Queue\SerializesModels;

/**
 * Class AbstractNotification.
 *
 * @package Seat\Notifications\Jobs
 */
abstract class AbstractNotification extends AbstractNotificationJob
{
    use SerializesModels;

    /**
     * {@inheritdoc}
     */
    public $queue = 'notifications';

    public $mentions;

    /**
     * Get the notification's delivery channels.
     *
     * @param  $notifiable
     * @return array
     */
    abstract public function via($notifiable);

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     * The rate limiter can delay it many times, so $tries is not optimal
     * At the same time, retryUntil is not wise either, as it might keep spamming discord's server with erroneous requests.
     * $maxExceptions is perfect: the rate limiter can do his job, and we fail with errors.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * @return mixed
     */
    public function getMentions()
    {
        return $this->mentions ?? collect();
    }

    /**
     * @param  mixed  $mentions
     */
    public function setMentions($mentions): void
    {
        $this->mentions = $mentions;
    }

    /**
     * Return the identifying payload used for duplicate suppression.
     * Return an associative array describing the event identity, or null to
     * skip suppression for this notification instance.
     *
     * @return array<string, mixed>|null
     */
    public function duplicateSuppressionKey()
    {
        return [
            'notification' => static::class,
            'properties' => collect((array) $this)
                ->mapWithKeys(function ($value, $key) {
                    if (! str_contains($key, "\0"))
                        return [$key => $value];

                    return [substr($key, strrpos($key, "\0") + 1) => $value];
                })
                ->except([
                    'mentions',
                    'job',
                    'queue',
                    'connection',
                    'delay',
                    'afterCommit',
                    'chained',
                    'chainConnection',
                    'chainQueue',
                    'chainCatchCallbacks',
                    'middleware',
                    'tries',
                    'timeout',
                    'maxExceptions',
                    'locale',
                ])
                ->sortKeys()
                ->all(),
        ];
    }
}
