<template>
	<div :id="'reply-'+id" class="card">
	    <div class="card-header">
	    	<div class="level">
	    		<h5 class="flex">
			        <a :href="'http://localhost/Laravel/forum/public/profiles/'+data.owner.name"
			        	v-text="data.owner.name">
			        </a> said {{ data.created_at }}...
		        </h5>

		        <!--@auth
			        <div>
			        	<favorite :reply="{{ $reply }}"></favorite>
			        </div>
		        @endauth -->
	    	</div>
	    </div>

	    <div class="card-body">
	    	<div v-if="editing">
	    		<div class="form-group">
	    			<textarea class="form-control" v-model="body"></textarea>
	    		</div>

	    		<button class="btn btn-xs btn-primary" @click="update">Update</button>
	    		<button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
	    	</div>

	    	<div v-else v-text="body"></div>
	    </div>

	    <!-- @can('update', $reply) -->
		    <div class="card-footer level">
		    	<button class="btn btn-xs btn-outline-secondary mr-1" @click="editing=true">Edit</button>
		    	<button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
		    </div>
	    <!-- @endcan  -->
	</div>
</template>

<script>
	import Favorite from './Favorite.vue';

	export default {
		props: ['data'],

		components: { Favorite },

		data() {
			return {
				editing: false,
				id: this.data.id,
				body: this.data.body,
			};
		},

		methods: {
			update() {
				axios.patch('http://localhost/Laravel/forum/public/replies/' + this.data.id, {
					body: this.body
				});

				this.editing = false;
			},

			destroy() {
				axios.delete('http://localhost/Laravel/forum/public/replies/' + this.data.id);

				this.$emit('deleted', this.data.id);

				// $(this.$el).fadeOut(300);
			}
		}
	}
</script>	