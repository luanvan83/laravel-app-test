<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TaskCompletedRankCalculator implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    //public $connection = 'database';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    //public $queue = 'listeners';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 10;

    protected $task;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
        Log::info('TaskCompletedRankCalculator', [
            __FILE__,
            __FUNCTION__
        ]);
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCompleted $event): void
    {
        $this->task = $event->getTask();
        Log::info('TaskCompletedRankCalculator', [
            __FILE__,
            __FUNCTION__,
            $this->task
        ]);

        // Do something heavy
        // For example re-calculate user rank
    }
}
