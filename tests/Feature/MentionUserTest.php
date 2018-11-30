<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUserTest extends TestCase
{
   use RefreshDatabase;

   /** @test */
   function mention_users_in_a_reply_are_notified()
   {
       $join = create('App\User', ['name' => 'JohnDoe']);

       $this->signIn($join);

       $jain = create('App\User', ['name' => 'JaneDoe']);

       $thread = create('App\Thread');

       $reply = make('App\Reply',[
           'body' => '@JaneDoe look at this. @FrankDoe'
       ]);

       $this->json('post', $thread->path() . '/replies', $reply->toArray());

       $this->assertCount(1, $jain->notifications);
   }
}
