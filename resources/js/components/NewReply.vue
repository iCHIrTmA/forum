<template>
	<div>
	    <div class="form-group">
	        <textarea 
	        	name="body" 
	        	id="body" 
	        	class="form-control" 
	        	placeholder="Have something to say?" 
	        	rows="5"
	        	required
	        	v-model="body"></textarea>

	    </div>
        <button 
        	type="submit" 
        	class="btn btn-primary"
        	@click="addReply">Post</button>

		<!-- <p class="text-center">Please <a href="{{ url('login')}}">sign in</a> to participate in this  -->
	</div>
</template>
<script>
	export default {
		data() {
			return {
				body: '',
				endpoint: 'http://localhost/Laravel/forum/public/threads/voluptatem/24/replies',
			};
		},

		methods: {
			addReply() {
				axios.post(this.endpoint, { body: this.body })
					.then(({data}) => {
						this.body = '';

						flash('Your reply has been submitted');

						this.$emit('created', data);
					});
			}			
		}
	}
</script>