<template>
	<div :id="'reply-'+id" class="card">
	    <div class="card-header" :class="isBest ? 'bg-success' : ''">
	    	<div class="level">
	    		<h5 class="flex">
			        <a :href="'http://localhost/Laravel/forum/public/profiles/'+reply.owner.name"
			        	v-text="reply.owner.name">
			        </a> said <span v-text="ago"></span>
		        </h5>

		        <div v-if="signedIn">
		        	<favorite :reply="reply"></favorite>
		        </div>

	    	</div>
	    </div>

	    <div class="card-body">
	    	<div v-if="editing">
	    		<form @submit="update">
		    		<div class="form-group">
		    			<textarea class="form-control" v-model="body" required></textarea>
		    		</div>

		    		<button class="btn btn-xs btn-primary">Update</button>
		    		<button class="btn btn-xs btn-link" type="button" @click="editing = false">Cancel</button>
	    		</form>
	    	</div>

	    	<div v-else v-html="body"></div>
	    </div>

	    <!-- @can('update', $reply) -->
		    <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
		    	<div  v-if="authorize('owns', reply)">
		    		<button class="btn btn-xs btn-outline-secondary mr-1" @click="editing=true">Edit</button>
		    		<button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
		    	</div>
		    	<button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply?</button>
		    </div>
	    <!-- @endcan  -->
	</div>
</template>

<script>
	import Favorite from './Favorite.vue';
	import moment from 'moment';

	export default {
		props: ['reply'],

		components: { Favorite },

		data() {
			return {
				editing: false,
				id: this.reply.id,
				body: this.reply.body,
				isBest: this.reply.isBest,
			};
		},

		computed: {
			ago() {
				return moment(this.reply.created_at).fromNow() + '...';
			},
		},

		created () {
			window.events.$on('best-reply-selected', id => {
				this.isBest = (id === this.id)
			});
		},

		methods: {
			update() {
				axios.patch('http://localhost/Laravel/forum/public/replies/' + this.reply.id, {
					body: this.body
				})
					.catch(error => {
						flash(error.response.data, 'danger');
				});

				this.editing = false;
			},

			destroy() {
				axios.delete('http://localhost/Laravel/forum/public/replies/' + this.reply.id);

				this.$emit('deleted', this.reply.id);

				$(this.$el).fadeOut(300);
			},

			markBestReply() {
				axios.post('http://localhost/Laravel/forum/public/replies/' + this.reply.id + '/best');

				window.events.$emit('best-reply-selected', this.reply.id);
			}
		}
	}
</script>	