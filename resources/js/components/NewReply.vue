<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body" 
                class="form-control"
                    placeholder="Say?" 
                    rows="5" 
                    required
                    v-model="body"></textarea>
            </div>
            <button type="submit" class="btn btn-default" 
            @click="addReply">Post</button>
        </div>                  
        <p class="text-center" v-else>
            Please <a href="/login/">sign in</a> to participate in this discussion.
        </p>
        
    </div>
    
</template>
<script>
export default {
    data(){
        return{
            body:''
        }
    },
    computed:{
        signedIn(){
            return window.App.signedIn;
        }
    },
    methods: {
        addReply(){
            axios.post(location.pathname + '/replies', {body: this.body})
            .then(response => {
                this.body = '';

                flash('Your reply has been posed!');

                this.$emit('created', response.data);
            })
        }
    }
}
</script>

