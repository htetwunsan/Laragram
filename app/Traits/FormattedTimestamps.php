<?php

namespace App\Traits;

use Carbon\CarbonInterface;

trait FormattedTimestamps
{
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->diffForHumans(null, CarbonInterface::DIFF_ABSOLUTE, true);
    }

    public function getFormattedUpdateAtAttribute(): string
    {
        return $this->updated_at->diffForHumans(null, CarbonInterface::DIFF_ABSOLUTE, true);
    }
}
