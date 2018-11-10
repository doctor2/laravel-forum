<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ReadThreadsTest extends TestCase
{

    use DatabaseMigrations;

    function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
   /**
    @test
    */
    public function a_user_can_all_threads()
    {

        $response = $this->get('/threads')
            ->assertSee($this->thread->title);
        // $response->assertStatus(200);

    }
     /**
    @test
    */
    public function a_user_can_single_threads()
    {
        $response = $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }
    /**
     * @test
     */
    public function a_user_can_read_replies()
    {
        $reply = factory('App\Reply')->create(['thread_id' =>$this->thread->id]);
        $this->get($this->thread->path())
            ->assertSee($reply->body);

    }

    /**
     @test
     */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /**
     @test
     */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

}
