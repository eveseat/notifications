<?php

namespace Seat\Notifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
                $this->model->attackers->pluck('corporation_id')
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
