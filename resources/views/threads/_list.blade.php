            @forelse ($threads as $thread)
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <div class="flex">                              
                                <h4>
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
                                <h6>
                                    Posted by <a href="{{ route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a>
                                </h6>
                            </div>

                            <a href="{{url( $thread->path() )}}">
                                {{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="body">{{ $thread->body }}</div>
                    </div>

                    <div class="card-footer">
                        100 visits
                    </div>
                </div>
            @empty
                <p>No threads yet</p>
            @endforelse