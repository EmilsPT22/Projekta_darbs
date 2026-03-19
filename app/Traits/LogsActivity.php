<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Log an activity.
     */
    protected function logActivity(
        string $action,
        string $description,
        $subject = null,
        array $properties = []
    ): void {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->id,
            'properties' => $properties ?: null,
        ]);
    }
}
