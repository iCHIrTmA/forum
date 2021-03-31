@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('threads._list')

            {{ $threads->render() }}
        </div>

        <div class="col-md-4">
        	<div class="card">
        		<div class="card-header">	
        			Trending Threads
        		</div>
        		<div class="card-body">
        			notes
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection
