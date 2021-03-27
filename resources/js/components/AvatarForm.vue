<template>
<div>
	<h1 v-text="user.name"></h1>

		<form v-if="canUpdate"method="POST" enctype="multipart/form-data">
			<input type="file" name="avatar">
			<button type="submit">Add Avatar</button>							
		</form>

		<img :src="avatar" width="50">
</div>
</template>

<script>
	export default {
		props: ['user'],

		data() {
			return {
				avatar: ''
			};
		},

		computed: {
			canUpdate() {
				return this.authorize(user => user.id === this.user.id)
			}
		}
	}	
</script>