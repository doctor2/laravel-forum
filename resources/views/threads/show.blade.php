@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
@endsection

@section('content')
<thread-view inline-template :initial-replies-count="{{$thread->replies_count}}">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a> posted: 
                            {{$thread->title}}
                        </span>
                        @can ('update', $thread)
                            <form action="{{$thread->path()}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link">Delete thread</button>
                            </form>
                        @endcan
                      
                    </div>
                   
                </div>

                <div class="card-body">
                    {{$thread->body}}
                </div>
            </div>

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
                    <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                    </p>
                </div>
            </div>
        </div>
    </div>


  
</div>
</thread-view>
@endsection
