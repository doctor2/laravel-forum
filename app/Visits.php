<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits 
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    public function record()
    {
        Redis::incr($this->cachKey());

        return $this;
    }

    public function reset()
    {
        Redis::del($this->cachKey());
        
        return $this;
    }

    public function count()
    {
        return Redis::get($this->cachKey()) ?? 0;  
    }   

    protected function cachKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}