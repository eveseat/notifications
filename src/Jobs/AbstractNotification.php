<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Get the notification's delivery channels.
     *
     * @param  $notifiable
     * @return array
     */
    abstract public function via($notifiable);

    public $mentions;

    /**
     * @return mixed
     */
    public function getMentions()
    {
        return $this->mentions ?? collect();
    }

    /**
     * @param mixed $mentions
     */
    public function setMentions($mentions): void
    {
        $this->mentions = $mentions;
    }

}
