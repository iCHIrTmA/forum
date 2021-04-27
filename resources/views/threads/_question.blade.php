{{-- Editing --}}
<div class="card" v-if="editing">
    <div class="card-header">
        <div class="level">
            <input class="form-control"type="text" v-model="form.title">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="form.body"></textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <button class="btn btn-xs btn-outline-secondary level-item" @click="editing = true" v-show="! editing">Edit</button>
            <button class="btn btn-xs btn-primary level-item" @click="update">Update</button>
            <button class="btn btn-xs btn-outline-secondary level-item" @click="resetForm">Cancel</button>
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
                <span v-text="title"></span>
            </h5>
        </div>
    </div>

    <div class="card-body" v-text="body"></div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs btn-outline-secondary" @click="editing = true">Edit</button>
    </div>
</div>