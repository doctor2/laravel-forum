@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
@endsection

@section('content')
<thread-view inline-template :thread="{{$thread}}">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

         @include('threads._question')

        <replies @added="repliesCount++" @removed="repliesCount--"></replies>
            {{-- @foreach ($replies as $reply)
                @include('threads._reply')
                <br>
            @endforeach

            {{$replies->links()}} --}}
            
            {{-- @if (auth()->check())
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form action="{{$thread->path() . '/replies'}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea name="body" id="" class="form-control" placeholder="Say?" rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
            
                    </div>
                </div>
            @else
            <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate in this discussion.</p>
            @endif --}}

        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="{{route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a>, and currently
                        has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}
                        .
                    </p>
        
                    <p>
                        <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>
                        <button class="btn btn-default" 
                            v-if="authorize('isAdmin')" 
                            @click="toggleLock" 
                            v-text="locked ? 'Unlock': 'Lock'"></button>
                    </p>
                </div>
            </div>
        </div>
    </div>


  
</div>
</thread-view>
@endsection
