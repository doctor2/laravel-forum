<?/*?>
<reply :attributes="{{$reply}}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}">
        <div  class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner) }}">{{$reply->owner->name }}</a> said {{$reply->created_at->diffForHumans()}}
                </h5>
                <div>
                    @if (Auth::check())
                    <favorite :reply="{{$reply}}"></favorite>                        
                    @endif
                    {{-- <form method="POST" action="/replies/{{$reply->id}}/favorites">
                        @csrf
                    <button type="submit" class="btn btn-default " {{ $reply->isFavorited() ? 'disabled' : ''}}>
                        {{$reply->favorites_count }} {{str_plural('Favorite', $reply->favorites_count)}} </button>
                    </form> --}}
                </div>
            </div>
        </div>

        <div class="card">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing=false">Cancel</button>
            </div>
            <div v-else class="card-body" v-text="body">
                {{-- {{$reply->body}} --}}
            </div>
        </div>
        @can('update', $reply)
            <div class="card-footer level">
                <button type="submit" @click="editing=true" class="btn btn-xs">Edit</button>
                <button type="submit" @click="destroy" class="btn btn-danger btn-xs">Delete</button>

                {{-- <form method="POST" action="/replies/{{$reply->id}}">

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                </form> --}}
            </div>
        @endcan
    </div>
</reply>
<?*/?>
