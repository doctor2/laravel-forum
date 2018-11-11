<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function isFavorited()
    // {
    //     // return $this->favorites()->where('user_id', auth()->id())->exists();
    //     return !!$this->favorites->where('user_id', auth()->id())->count();
    // }
}
