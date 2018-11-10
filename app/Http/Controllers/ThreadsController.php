<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Filters\ThreadFilters;
use App\Channel;
use Illuminate\Http\Request;

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
    public function index(Channel $channel, ThreadFilters $filter)
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

        $threads = Thread::latest()->filter($filter);

        if($channel->exists)
        {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->get();
        
        return view('threads.index', compact('threads'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);
        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread)
    {
        // Thread::withCount('replies')->first();
        return view('threads.show', [
            'thread' => $thread,
            'replies'=> $thread->replies()->paginate(5)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
