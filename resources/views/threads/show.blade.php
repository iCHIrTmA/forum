@extends('layouts.app')

@section('header')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                        @if($thread->creator->avatar_path)
                            <img src="{{ $thread->creator->avatar_path }}" width="25">
                        @endif
                            <h5 class="flex">
                                <a href="{{ url('profiles/' . $thread->creator->name) }}"> 
                                    {{ $thread->creator->name }} 
                                </a> posted
                                {{ $thread->title }}
                            </h5>

                            @can('update', $thread)   
                                <form action="{{ url($thread->path()) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <replies @added="repliesCount++" @removed="repliesCount--"></replies>                
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published{{ $thread->created_at->diffForHumans() }} by 
                            <a href="#">{{ $thread->creator->name }}</a>, and currently has <span v-text="repliesCount"></span>
                            {{ Str::plural('comment', $thread->replies_count) }}.
                        </p>

                        <p>
                            <subscribe-button :active="{{ $thread->isSubscribedTo ? 'true' : 'false' }}" v-if="signedIn"></subscribe-button>

                            <button class="btn btn-default" 
                            v-if="authorize('isAdmin')" 
                            @click="toggleLock" 
                            v-text="locked ? 'Unlock' : 'Lock'">
                            </button>
                        </p>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</thread-view>
@endsection
