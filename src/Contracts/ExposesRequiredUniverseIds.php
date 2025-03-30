<?php

namespace Seat\Notifications\Contracts;

use Illuminate\Support\Collection;

interface ExposesRequiredUniverseIds
{
    /**
     * Get the universe IDs that must be resolved before the notification can be sent.
     *
     * @return Collection
     */
    public function getRequiredUniverseIds(): Collection;
}