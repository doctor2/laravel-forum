<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function guest_may_not_create_threads()
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

    /** @test */
    public function a_user_can_creata_new_forum_threads()
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

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())->assertStatus(403);

    }

    /** @test */
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread),
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply),
        ]);

        $this->assertEquals(0, Activity::count());
    }

    // /** @test */
    // public function a_thread_can_be_deleted()
    // {
    //     $this->signIn();

    //     $thread = create('App\Thread');
    //     $reply = create('App\Reply', ['thread_id' => $thread->id]);

    //     $response = $this->json('DELETE', $thread->path());

    //     $response->assertStatus(204);

    //     $this->assertDatabaseMissing('threads', [ 'id' => $thread->id]);
    //     $this->assertDatabaseMissing('replies', [ 'id' => $reply->id]);
    // }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
