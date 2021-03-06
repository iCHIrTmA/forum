@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @forelse ($threads as $thread)
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <h4 class="flex">
                                <a href="{{url($thread->path())}}"> 
                                    @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                        <strong>
                                            {{ $thread->title }}
                                        </strong>
                                    @else
                                            {{ $thread->title }}
                                    @endif
                                </a>
                            </h4>

                            <a href="{{url( $thread->path() )}}">
                                {{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="body">{{ $thread->body }}</div>
                    </div>
                </div>
            @empty
                <p>No threads yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
