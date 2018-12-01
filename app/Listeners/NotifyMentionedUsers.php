<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $names = $event->reply->mentionedUsers();

        foreach($names as $name)
        {
            $user = \App\User::whereName($name)->first();

            if($user){
                $user->notify(new YouWereMentioned($event->reply));
            }
        }
    }
}
