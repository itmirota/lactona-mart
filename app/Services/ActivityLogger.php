<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        array $properties = []
    ): ActivityLog {
        $log = new ActivityLog();

        $log->user_id = Auth::id();
        $log->action = $action;
        $log->module = $module;
        $log->description = $description;
        $log->properties = $properties;

        if ($subject) {
            $log->subject()->associate($subject);
        }

        $log->save();

        return $log;
    }
}
