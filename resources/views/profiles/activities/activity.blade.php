<div class="card">
        <div class="card-header">
            <div class="level">
                <span class="flex">
                    {{$heading}}
               {{-- <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a> posted: 
                    <a href="{{$thread->path()}}">{{$thread->title}}</a>     --}}
                </span>
                {{-- <span>{{$thread->created_at->diffForHumans()}}</span> --}}
            </div>
            
        </div>
    
        <div class="card-body">
            {{$body}}
        </div>
    </div>
    <br>