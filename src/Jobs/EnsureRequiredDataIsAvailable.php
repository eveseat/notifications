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

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Seat\Services\Models\ExtensibleModel;

class EnsureRequiredDataIsAvailable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $notificationType;
    public ExtensibleModel $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $notificationType, ExtensibleModel $model)
    {
        $this->notificationType = $notificationType;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $requiredIds = match ($this->notificationType) {
            'Killmail' => collect([
                $this->model->victim->character_id,
                $this->model->victim->corporation_id,
                $this->model->attackers->pluck('character_id'),
                $this->model->attackers->pluck('corporation_id'),
            ])->flatten()->unique()->filter(),
            default => collect(),
        };

        if ($requiredIds->isEmpty())
            return;

        $request = new Request(['ids' => $requiredIds->join(',')]);
        app(Seat\Web\Http\Controllers\Support\ResolveController::class)->resolveIdsToNames($request);
    }

    /**
     * Assign this job a tag so that Horizon can categorize and allow
     * for specific tags to be monitored.
     *
     * @return array
     */
    public function tags(): array
    {
        return ['notifications', 'other'];
    }

}
