<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{

    use RefreshDatabase;

     /**
     @test
     */
    function guest_may_not_create_threads()
    {
        $this->withExceptionHandling();


        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    // /**
    //  @test
    //  */
    // function guest_may_not_create_threads()
    // {
    //     $this->expectException('Illuminate\Auth\AuthenticationException');

    //     $thread = factory('App\Thread')->make();

    //     $this->post('/threads', $thread->toArray());
    // }
    // /**
    //  @test
    // */
    // function guest_cannot_see_the_create_thread_page()
    // {
    //     $this->withExceptionHandling()
    //         ->get('/threads/create')
    //         ->assertRedirect('/login');
    // }


    /**
     @test
     */
    public function an_anthenticated_user_can_creata_new_forum_threads()
    {
        // $this->actingAs(create('App\User'));
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
        // $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     @test
     */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

      /**
     @test
     */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
