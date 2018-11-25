<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

   /** @test */
   function a_notification_is_prepared_when_a_ssubscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
   {

       $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply here',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);

        // $this->assertCount(1, $thread->subscriptions);
   }

   /** @test */
   function a_user_can_fetch_their_unread_notifications()
   {
        create(DatabaseNotification::class);
        // $thread = create('App\Thread')->subscribe();

        // $thread->addReply([
        //     'user_id' => create('App\User')->id,
        //     'body' => 'some reply here',
        // ]);

        $user = auth()->user();

        $response = $this->getJson("/profiles/". $user->name . "/notifications/")->json();

        $this->assertCount(1, $response);
   }

   /** @test */
    function a_user_can_mark_a_notification_as_read()
    {
        create(DatabaseNotification::class);

        tap(auth()->user(), function($user){
            $this->assertCount(1, $user->unreadNotifications);
        
            $notificationId = $user->unreadNotifications->first()->id;
    
            $this->delete("/profiles/". $user->name . "/notifications/{$notificationId}");
    
            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    } 

}
