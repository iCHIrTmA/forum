<div class="card">
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
        {{ $reply->body }}
    </div>
</div>