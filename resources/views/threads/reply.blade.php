<reply :attributes="{{ $reply }}" inline-template>
	<div id="reply-{{ $reply->id }}" class="card">
	    <div class="card-header">
	    	<div class="level">
	    		<h5 class="flex">
			        <a href="{{ url('profiles/' . $reply->owner->name) }}"> {{ $reply->owner->name }} </a> 
			        said {{ $reply->created_at->diffForHumans() }}...
		        </h5>

		        <div>
		        	<form method="POST" action="{{url('/replies/' . $reply->id . '/favorites')}}">
		        		@csrf
		        		@method('POST')
		        		<button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
		        			{{ $reply->favorites_count }} {{ Str::plural('Favorite', $reply->favorites_count) }}
		        		</button>
		        	</form>
		        </div>
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

	    @can('update', $reply)
		    <div class="card-footer level">
		    	<button class="btn btn-xs btn-outline-secondary mr-1" @click="editing=true">Edit</button>
		    	<button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
		    </div>
	    @endcan
	</div>
</reply>