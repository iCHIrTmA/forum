@extends('layouts.app')

@section('header')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Thread</div>

                <div class="card-body">
                    <form method="POST" action="{{url('/threads')}}">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="channel_id">Choose a channel</label>
                            <select class="form-control" name="channel_id" id="channel_id" required>
                                <option value="">Choose One....</option>

                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : ''}}>{{ $channel->name }}</option>
                                @endforeach
                            </select>
                        </div>  

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title')}}" required>
                        </div>                        

                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea type="text" name="body" id="body" class="form-control" rows="8" placeholder="Something to say?" required>{{ old('body')}}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <form action="?" method="POST">
                                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                                    <br/>
                                <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                                </div>
                            </form>
                        </div>



                        @if (count($errors))
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                         @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection