<template>
<div>
	<h1 v-text="user.name"></h1>

		<form v-if="canUpdate"method="POST" enctype="multipart/form-data">
			<image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
			<button type="submit" @click.prevent="persist">Add Avatar</button>							
		</form>

		<img :src="avatar" width="50">
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
				fileUploaded: '',
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
				this.fileUploaded = avatar.file;
			},
			persist() {
				let data = new FormData();

				data.append('avatar', this.fileUploaded)

				axios.post(`http://localhost/Laravel/forum/public/api/users/${this.user.name}/avatar`, data)
					.then(() => flash('Avatar uploaded!'));
			}
		}
	}	
</script>