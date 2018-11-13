<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{

    use RefreshDatabase;


    /** @test */
    public function guest_can_not_favorite_anything()
    {
        create('App\Reply');

        $this
            ->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }
    /**
     * @test
     */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('replies/' . $reply->id . '/favorites');

        // dd(\App\Favorite::all());

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }

        // dd(\App\Favorite::all()->toArray());
        $this->assertCount(1, $reply->favorites);
    }
}