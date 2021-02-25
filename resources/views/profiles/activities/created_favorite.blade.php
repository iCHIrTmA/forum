@component('profiles.activities.activity')
	@slot('heading')
		{{ $profileUser->name }} favorited a <a href="{{ url( $activity->subject->favorited->path() )}}">reply</a>
	@endslot
	@slot('body')
	    {{ $activity->subject->favorited->body }}
	@endslot
@endcomponent