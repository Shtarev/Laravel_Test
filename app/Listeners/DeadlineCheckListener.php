<?php

namespace App\Listeners;

use App\Events\DeadlineCheckEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OverdueTaskeNotification;

class DeadlineCheckListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeadlineCheckEvent $event): void
    {
        $task = $event->task;
        $title = $task->title;
        $taskId = $task->id;

        $currentDate = Carbon::now();
        $deadlineDate = Carbon::parse($task->deadline);

        $dlmessage = ($task->dlmessage === null) ? false : Carbon::parse($task->dlmessage)->isToday();

        $diffInDays = $currentDate->diffInDays($deadlineDate);

        if($diffInDays < 0 && $dlmessage === false && $task->status != 'done') {
            $event->task->update(['dlmessage' => Carbon::now()->toDateTimeString()]);
            Notification::send($event->task->user()->first(), new OverdueTaskeNotification($title, $taskId));
        }
    }
}
