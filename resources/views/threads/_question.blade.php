<div class="card" v-if="editing" v-cloak>
        <div class="card-header">
            <div class="level">
                <input type="text" class="form-control" v-model="form.title" >
            </div>
        </div>

        <div class="card-body">
            <div class="form-group">
                <textarea class="form-control" v-model="form.body" rows="10"></textarea>
            </div>
        </div>

        <div class="card-footer">
            <div class="level">

                    <button class="btn btn-xs mr-1" @click="editing=true" v-show="!editing">Edit</button>
                    <button class="btn btn-primary btn-xs mr-1" @click="update" v-show="editing">update</button>
                    <button class="btn btn-xs mr-1" @click="resetForm">Cancel</button>

                    @can ('update', $thread)
                    <form action="{{$thread->path()}}" method="POST" class="ml-a">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link">Delete thread</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="card" v-else>
            <div class="card-header">
                <div class="level">
                <img src="{{ $thread->creator->avatar_path }}" alt="{{$thread->creator->name}}" width="25" height="25" class="mr-1">
    
                  
                    <span class="flex">
                        <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a> posted: <span v-text="title"></span>                    
                    </span>
                    {{-- @can ('update', $thread)
                        <form action="{{$thread->path()}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link">Delete thread</button>
                        </form>
                    @endcan --}}
                  
                </div>
               
            </div>
    
            <div class="card-body" v-text="body">
                
            </div>
            <div class="card-footer" v-if="authorize('owns', thread)">
                <button class="btn btn-xs" @click="editing=true">Edit</button>
            </div>
        </div>