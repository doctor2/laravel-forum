<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Filters\ThreadFilters;
use App\Channel;
use Illuminate\Http\Request;
use App\Trending;
use App\Rules\Recaptcha;

class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);//->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filter, Trending $trending)
    {
          // if($channelSlug)
        // {
        //     $channelId = Channel::where('slug', $channelSlug)->first()->id;

        //     $threads = Thread::where('channel_id', $channelId)->latest()->get();
       
        // if($channel->exists)
        // {
        //     $threads = $channel->threads()->latest();
        // }
        // else
        // {
        //     $threads = Thread::latest();
        // }

        // if($username = request('by'))
        // {
        //     $user = \App\User::where('name', $username)->firstOrFail();

        //     $threads->where('user_id', $user->id);
        // }

        // $threads = $threads->get();

        // $threads = Thread:://with('channel')->
        // latest()->filter($filter);

        // if($channel->exists)
        // {
        //     $threads->where('channel_id', $channel->id);
        // }

        // $threads = $threads->get();

        $threads = $this->getThreads($channel, $filter);

        if(request()->wantsJson())
        {
            return $threads;
        }
        // dd($threads->toSql());
        
        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Recaptcha $recaptcha)
    {

        request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
            // 'g-recaptcha-response' => ['required', $recaptcha],
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        if(request()->wantsJson()){
            return response($thread, 201);
        }

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        if(auth()->check())
        {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->increment('visits');

        return view('threads.show', compact('thread'));
    }

    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
        ]));

        return $thread;
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if(request()->wantsJson())
        {
            return response([], 204);
        }

        return redirect('/threads');
    }

    public function getThreads($channel, $filter)
    {
        $threads = Thread::latest()
            ->filter($filter);

        if($channel->exists)
        {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(5);
    }
}
