<template>
    <div :id="'reply-'+id" class="card" :class="isBest ? 'card-success' : ''">
        <div  class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name">
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

        
        <div v-if="editing">
            <form @submit.prevent="update">
                <div class="form-group">
                    <textarea class="form-control" v-model="body" required></textarea>
                </div>
                <button class="btn btn-xs btn-primary" >Update</button>
                <button class="btn btn-xs btn-link" @click="editing=false" type="button">Cancel</button>
            </form>
        </div>
        <div v-else class="card-body" v-html="body">
            <!-- {{-- {{$reply->body}} --}} -->
        </div>
        <!-- @can('update', $reply) -->
        <div class="card-footer level">
            <div  v-if="authorize('updateReply', reply)">
                <button @click="editing=true" class="btn btn-xs mr-1">Edit</button>
                <button @click="destroy" class="btn btn-danger btn-xs mr-1">Delete</button>
            </div>

            <button class="btn btn-default btn-xs ml-a" @click="markBestReply" v-show="!isBest">Best Reply?</button>

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
            body: this.data.body,
            isBest: this.data.isBest,
            reply: this.data
        }
    },
    computed:{
        ago(){
            return moment(this.data.created_at).fromNow()+ '...';
        },
        // signedIn(){
        //     return window.App.signedIn;
        // },
        // canUpdate(){
        //     return this.authorize(user => this.data.user_id == user.id);
        //     // return window.App.user.id == this.data.user_id;
        // }
    },
    created(){
        window.events.$on('best-reply-selected', id => {
            this.isBest = id === this.id;
        })
    },
    methods:{
        update(){
            axios.patch('/replies/' + this.data.id,{
                body: this.body
            })
            .catch(error=>{
                flash(error.response.data, "danger");
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
        },
        markBestReply(){
            this.isBest = true;

            axios.post('/replies/' + this.data.id + '/best');

            window.events.$emit('best-reply-selected', this.data.id);
        }
    }
}
</script>
