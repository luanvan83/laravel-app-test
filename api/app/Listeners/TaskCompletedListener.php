<?php

namespace App\Listeners;

use App\Notifications\TaskCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TaskCompletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
        Log::info('TaskCompletedListener', [
            __FILE__,
            __FUNCTION__
        ]);
    }

    /**
     * Handle the event.
     * @param \App\Events\TaskCompleted
     */
    public function handle(object $event): void
    {
        $mTask = $event->getTask();
        $to = $mTask->owner;
        Log::info('TaskCompletedListener', [
            __FILE__,
            __FUNCTION__,
            $mTask
        ]);

        Notification::send([$to], new TaskCompletedNotification($mTask));
    }
}
