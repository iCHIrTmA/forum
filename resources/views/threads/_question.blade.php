{{-- Editing --}}
<div class="card" v-if="editing">
    <div class="card-header">
        <div class="level">
            <input class="form-control"type="text" value="{{ $thread->title }}">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="10">{{ $thread->body }}</textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <button class="btn btn-xs btn-outline-secondary level-item" @click="editing = true" v-show="! editing">Edit</button>
            <button class="btn btn-xs btn-primary level-item" @click="editing = true">Update</button>
            <button class="btn btn-xs btn-outline-secondary level-item" @click="editing = false">Cancel</button>
            @can('update', $thread)   
                <form action="{{ url($thread->path()) }}" method="POST" class="ml-a">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{-- NOT EDiting --}}
<div class="card" v-else>
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
        </div>
    </div>

    <div class="card-body">
        {{ $thread->body }}
    </div>

    <div class="card-footer">
        <button class="btn btn-xs btn-outline-secondary" @click="editing = true">Edit</button>
    </div>
</div>