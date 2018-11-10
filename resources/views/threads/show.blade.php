@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header">{{$thread->title}}</div>

                <div class="card-body">
                    {{$thread->body}}
                </div>
            </div>

            @foreach ($thread->replies as $reply)
                @include('threads._reply')
            @endforeach

            @if (auth()->check())
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
            @endif

        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="#">{{ $thread->creator->name }}</a>, and currently
                        has {{$thread->replies_count}} {{ str_plural('comment', $thread->replies_count) }}
                        .
                    </p>
        
                    <p>
                        {{-- <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button> --}}
        
                        {{-- <button class="btn btn-default" --}}
                                {{-- v-if="authorize('isAdmin')" --}}
                                {{-- @click="toggleLock"
                                v-text="locked ? 'Unlock' : 'Lock'"></button> --}}
                    </p>
                </div>
            </div>
        </div>
    </div>


  
</div>


@endsection
