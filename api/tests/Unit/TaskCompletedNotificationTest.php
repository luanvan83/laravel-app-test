<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskCompletedNotification;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskCompletedNotificationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_noti(): void
    {
        $mUser = User::factory()->create();
        Notification::fake();
        $mTask = new Task();
        $mTask->fill([
            'name' => 'Test task',
            'user_id' => $mUser->id, 
        ]);
        
        $mTask->markCompleted();
        
        Notification::assertSentTo(
            [$mUser], TaskCompletedNotification::class
        );
        
        Notification::assertSentTo(
            [$mUser],
            function (TaskCompletedNotification $notification, array $channels) use ($mTask) {
                return $notification->getTask()->name === $mTask->name;
            }
        );
    }
}
