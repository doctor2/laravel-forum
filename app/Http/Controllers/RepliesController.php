<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreatePostRequest;
use App\User;
use App\Notifications\YouWereMentioned;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }

    public function store($channelId, Thread $thread, CreatePostRequest $request)
    {
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);

        foreach($matches[1] as $name)
        {
            $user = User::whereName($name)->first();

            if($user){
                $user->notify(new YouWereMentioned($reply));
            }
        }
        return $reply->load('owner');

        // if(Gate::denies('create', new Reply)){
        //     return response(
        //         'You are posting too frequently. Please take a brake!', 429
        //     );
        // }

        // try {
        //     // $this->authorize('create', new Reply);
        //     $this->validate(request(), ['body' => 'required|spamfree']);

        //     $reply = $thread->addReply([
        //         'body' => request('body'),
        //         'user_id' => auth()->id(),
        //     ]);
        // } catch (\Exception $e) {
        //     return response(
        //         'Sorry, your reply could not be saved at this time.',
        //         422
        //     );
        // }

        // return $reply->load('owner');

        // // if(request()->expectsJson())
        // // {
        // //     return $reply->load('owner');
        // // }

        // // return back()->with('flash', 'Your reply has been saved!');
    }

    public function destroy(Reply $reply)
    {
        // if($reply->user_id != auth()->id())
        // {
        //     return response([], 403);
        // }
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.',
                422
            );
        }
    }

    // public function validateReply()
    // {
    //     $this->validate(request(), ['body' => 'required']);

    //     resolve(Spam::class)->detect(request('body'));
    // }

}
