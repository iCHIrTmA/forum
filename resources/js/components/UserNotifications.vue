<template>
    <div class="dropdown" v-if="notifications.length">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
        	<i class="fa fa-bell"></i>
     	</a>

        <div class="dropdown-menu">
     		<li v-for="notification in notifications">
                <a class="dropdown-item" 
                href="http://localhost/Laravel/forum/public/"
                v-text="notification.data.message"
                @click="markAsRead(notification)"></a>
            </li>
        </div>
    </div>
	
</template>

<script type="text/javascript">
	export default {
		data() {
			return { notifications: false}
		},

		created() {
			axios.get("http://localhost/Laravel/forum/public/profiles/" + window.App.user.name + "/notifications")
				.then(response => this.notifications = response.data);
		},

		methods: {
			markAsRead(notification) {
				axios.delete("http://localhost/Laravel/forum/public/profiles/" + window.App.user.name + "/notifications/" + notification.id)
			},
		}
	}
</script>