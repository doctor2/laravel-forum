<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Inspections\Spam;
class SpamTest extends TestCase
{
   /**  @test */
   function it_checks_for_invalid_keywords()
   {
       $spam = new Spam();

       $this->assertFalse($spam->detect('Innocent reply here'));

       $this->expectException('Exception');

       $spam->detect('yahoo Customer Support');
   }

   /** @test */
   function it_checks_for_any_key_being_held_down()
   {
        $spam = new Spam();

       $this->expectException('Exception');

        $spam->detect('Hello word aaaaaaaaaaa');
   }
}
