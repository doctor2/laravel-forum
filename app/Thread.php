<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadReceivedNewReply;

class Thread extends Model
{

    use RecordsActivity;
    
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function($builder){
        //     $builder->withCount('replies');
        // });

        static::deleting(function($thread){
            // $thread->replies()->delete();
            // $thread->replies()->each(function($reply){
            //     $reply->delete();
            // });
            $thread->replies->each->delete();
        });

        static::created(function($thread){
            $thread->update(['slug' => str_slug($thread->title)]);
        });
    }

    public function path()
    {
        return '/threads/'. $this->channel->slug . '/' . $this->slug;
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class)
        // ->withCount('favorites')
        // ->with('owner')
        ;
    }

    // public function getReplyCountAttribute()
    // {
    //     return $this->replies()->count();
    // }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        // $this->notifySubscribers($reply);

        return $reply;
    }

    // public function notifySubscribers($reply)
    // {
    //     $this->subscriptions
    //     ->where('user_id', '!=', $reply->user_id)
    //     // ->filter(function($sub) use($reply){
    //     //     return $sub->user_id !=$reply->user_id;
    //     // })
    //     ->each->notify($reply);
    //     // foreach($this->subscriptions as $subscription)
    //     // {
    //     //     if($subscription->user_id != $reply->user_id)
    //     //     {
    //     //         $subscription->user->notify(new ThreadWasUpdated($this, $reply));
    //     //     }
    //     // }
    // }

    public function channel()
    {
        return $this->belongsTo(Channel::class);   
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId?: auth()->id()
        ]);

        return $this;
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId?: auth()->id())
            ->delete();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        // $slug = str_slug($value);
        // $count = 2;
        // $original = $slug;

        // while(static::whereSlug($slug)->exists()){
        //     $slug = $original . '-' . $count++;
        // }

        $slug = str_slug($value);

        if(static::whereSlug($slug)->exists()){
            $slug = $slug . '-' . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' =>  $reply->id ]);
    }
    
    public function incrementSlug($slug)
    {
        // $max = static::whereTitle($this->title)->latest('id')->value('slug');

        // if(is_numeric($max[-1])){
        //     return preg_replace_callback('/(\d+)$/', function($matches){
        //         return $matches[1] + 1;
        //     }, $max);
        // }

        // return "{$slug}-2";
    }

    // public function visits()
    // {
    //     return new \App\Visits($this);
    // }

}
