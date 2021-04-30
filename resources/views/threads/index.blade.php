@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('threads._list')

            {{ $threads->render() }}
        </div>

	    <div class="col-md-4">
        	@if(count($trending))
	        	<div class="card">
	        		<div class="card-header">	
	        			Search
	        		</div>

	        		<div class="card-body">
	        			<form method="GET" action="{{ url('/threads/search') }}">
	        				<div class="form-group">
	        					<input type="text" placeholder="Search for something..." name="q" class="form-control">
	        				</div>

	        				<div class="form-group">
	        					<button type="Submit" class="btn btn-primary">Search</button>
	        				</div>
	        			</form>
	        		</div>
	        	</div>
	        	<div class="card">
	        		<div class="card-header">	
	        			Trending Threads
	        		</div>

	        		<div class="card-body">
	        			@foreach ($trending as $thread)
	        				<li class="list-group-item"><a href="{{ url($thread->path)}}">{{ $thread->title }}</a></li>
	        			@endforeach
	        		</div>
	        	</div>
        	@endif
	    </div>
    </div>
</div>
@endsection
