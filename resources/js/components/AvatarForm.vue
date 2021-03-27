<template>
<div>
	<h1 v-text="user.name"></h1>

		<form v-if="canUpdate"method="POST" enctype="multipart/form-data">
			<input type="file" name="avatar" accept="image/*" @change="onChange">
			<button type="submit" @click.prevent="persist">Add Avatar</button>							
		</form>

		<img :src="avatar" width="50">
</div>
</template>

<script>
	export default {
		props: ['user'],

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
			onChange(e) {
				if(! e.target.files.length) return;

				let file = e.target.files[0];

				let reader = new FileReader();

				reader.readAsDataURL(file);

				reader.onload = e => {
					this.avatar = e.target.result
				};
				this.fileUploaded = file;
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