<template>
    <div :id="'reply-'+id">
        <div  class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profile/'+data.owner.name" v-text="data.owner.name">
                    </a> said <span v-text="ago"></span>
                </h5>
                <div>
                   <div v-if="signedIn">
                        <favorite :reply="data"></favorite>                        
                   </div>
                    <!-- {{-- <form method="POST" action="/replies/{{$reply->id}}/favorites"> 
                        @csrf
                    <button type="submit" class="btn btn-default " {{ $reply->isFavorited() ? 'disabled' : ''}}>
                        {{$reply->favorites_count }} {{str_plural('Favorite', $reply->favorites_count)}} </button>
                    </form> --}} -->
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
                <!-- {{-- {{$reply->body}} --}} -->
            </div>
        </div>
        <!-- @can('update', $reply) -->
            <div class="card-footer level" v-if="canUpdate">
                <button type="submit" @click="editing=true" class="btn btn-xs">Edit</button>
                <button type="submit" @click="destroy" class="btn btn-danger btn-xs">Delete</button>

                <!-- {{-- <form method="POST" action="/replies/{{$reply->id}}">

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                </form> --}} -->
            </div>
        <!-- @endcan -->
    </div>

</template>

<script>
import Favorite from './Favorite.vue';
import moment from 'moment';

export default {
    props:['data'],

    components:{ Favorite},
    data(){
        return {
            editing: false,
            id: this.data.id,
            body: this.data.body
        }
    },
    computed:{
        ago(){
            return moment(this.data.created_at).fromNow()+ '...';
        },
        signedIn(){
            return window.App.signedIn;
        },
        canUpdate(){
            return this.authorize(user => this.data.user_id == user.id);
            // return window.App.user.id == this.data.user_id;
        }
    },
    methods:{
        update(){
            axios.patch('/replies/' + this.data.id,{
                body: this.body
            });
            this.editing = false;
            flash('Updated!');
        },
        destroy(){
            axios.delete('/replies/' + this.data.id);

            this.$emit('deleted', this.data.id);
            // $(this.$el).fadeOut(300, ()=>{
            //     flash('Your reply has been  deleted!');
            // })
        }
    }
}
</script>
