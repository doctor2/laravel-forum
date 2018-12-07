<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class SsearshTest extends TestCase
{
    use RefreshDatabase;

    // /** @test */
    // function a_user_can_search_a_threads()
    // {
    //     config(['scout.driver' => 'algolia']);

    //     $search = 'foobar';

    //     create('App\Thread', [], 2);
    //     $desiredThreads = create('App\Thread', ['body' => "A thread with the {$search} term."], 2);

    //     do{
    //         sleep(.25);
            
    //         $results = $this->getJson("/threads/search?q={$search}")->json()['data'];
    //     }while(empty($results));

    //     $this->assertCount(2, $results);

    //     Thread::latest()->take()->unsearchable();
    // }
}
