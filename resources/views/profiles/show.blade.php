@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="page-header"> 
					<h1>
						{{ $profileUser->name }}
					</h1>

					@can('update', $profileUser)
						<form method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
							@csrf
							@method('POST')
							<input type="file" name="avatar">
							<button type="submit">Add Avatar</button>							
						</form>
					@endcan

					@if($profileUser->avatar())
						<img src="{{ asset('storage/' . $profileUser->avatar()) }}" width="50">
					@endif
				</div>

				@forelse ($activities as $date => $activity)
					<h3 class="page-header">{{ $date }}</h3>
					@foreach ($activity as $record)
						@if (view()->exists("profiles.activities.{$record->type}"))
							@include("profiles.activities.{$record->type}", ['activity' => $record])
						@endif
					@endforeach
				@empty
					<p>No activity for this user yet</p>
				@endforelse
			</div>				
		</div>
	</div>
@endsection