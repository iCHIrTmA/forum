<template>
<div>
	<div class="level">
		<img :src="avatar" width="50">
		
		<h1 v-text="user.name"></h1>
	</div>
			
	<form v-if="canUpdate"method="POST" enctype="multipart/form-data">
		<image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
		<button type="submit" @click.prevent="persist">Add Avatar</button>							
	</form>
</div>
</template>

<script>
	import ImageUpload from './ImageUpload.vue';
	export default {
		props: ['user'],

		components: { ImageUpload },

		data() {
			return {
				avatar: this.user.avatar_path,
				fileToUpload: '',
			};
		},

		computed: {
			canUpdate() {
				return this.authorize(user => user.id === this.user.id)
			}
		},

		methods: {
			onLoad(avatar) {
				this.avatar = avatar.src
				this.fileToUpload = avatar.file;
			},
			persist() {
				let data = new FormData();

				data.append('avatar', this.fileToUpload)

				axios.post(`http://localhost/Laravel/forum/public/api/users/${this.user.name}/avatar`, data)
					.then(() => flash('Avatar uploaded!'));
			}
		}
	}	
</script>