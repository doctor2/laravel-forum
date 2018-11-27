<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Spam;
class SpamTest extends TestCase
{
   /**  @test */
   function it_validates_span()
   {
       $spam = new Spam();

       $this->assertFalse($spam->detect('Innocent reply here'));
   }
}
