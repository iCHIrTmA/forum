@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
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

                <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

                {{-- {{ $replies->links() }} --}}
    
                @auth
                    <form method="POST" action="{{ url($thread->path() . '/replies') }}">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?" rows="5">                        
                            </textarea>

                            <button type="submit" class="btn btn-primary">Post</button>
                        </div>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{ url('login')}}">sign in</a> to participate in this discussion</p>
                @endauth
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published{{ $thread->created_at->diffForHumans() }} by 
                            <a href="#">{{ $thread->creator->name }}</a>, and currently has <span v-text="repliesCount"></span>
                            {{ Str::plural('comment', $thread->replies_count) }}.
                        </p>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</thread-view>
@endsection
