<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
	export default {
		props: ['active'],

		computed: {
			classes() {
				return ['btn', this.active ? 'btn-primary' : 'btn-default'];
			}
		},

		methods: {
			subscribe() {
				axios[
					(this.active ? 'delete' : 'post')
				](location.pathname + '/subscriptions');

				this.active ? flash('Unsubscribed') : flash('Subscribed');

				this.active = ! this.active;
			}
		}
	}
</script>