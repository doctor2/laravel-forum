<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    
    public function recordVisit()
    {
        Redis::incr($this->visitsCachKey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsCachKey()) ?? 0;  
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCachKey());
        
        return $this;
    }

    protected function visitsCachKey()
    {
        return "threads.{$this->id}.visits";
    }

}
