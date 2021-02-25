<div class="card">
    <div class="card-header">
    	<div class="level">
    		<span class="flex">
                {{ $heading }}
	        </span>

            <span> {{-- {{ $thread->created_at->diffForHumans() }}  --}}</span>
        </div>
    </div>

    <div class="card-body">
        {{ $body }}
    </div>
</div>