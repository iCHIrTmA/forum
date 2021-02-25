<div class="card">
    <div class="card-header">
    	<div class="level">
    		<span class="flex">
    			{{ $profileUser->name }} replied to a thread
	        </span>

            <span> {{-- {{ $thread->created_at->diffForHumans() }}  --}}</span>
        </div>
    </div>

    <div class="card-body">
        {{-- {{ $thread->body }} --}}
    </div>
</div>