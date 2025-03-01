<?php

namespace Tests\Feature\Notifications;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\OverdueTaskeNotification;

class OverdueTaskeNotificationTest extends TestCase
{
    /**
     * feature test Notification.
     */
    public function test_notification(): void
    {
        $user = User::factory()->create();

        Notification::fake();

        Notification::assertNothingSent();

        // Notification::assertSentTo(
        //     $user, ExampleNotification::class
        // );

        Notification::assertNotSentTo(
            $user, OverdueTaskeNotification::class
        );

        $user->delete();
    }
}
