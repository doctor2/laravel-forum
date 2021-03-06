@extends('layouts.app')
@section('styles')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Threads</div>

                <div class="card-body">
                  <form action="/threads" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="channel_id"> Channel:</label>
                        <select name="channel_id" id="channel_id">
                            <option value="">Choose one...</option>
                            @foreach ($channels as $channel)
                                <option value="{{$channel->id}}" {{old('channel_id') == $channel->id? 'selected' : ''}}>
                                    {{$channel->name}}
                                </option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for=""> Title:</label>
                    <input name="title" type="text" class="form-control" value="{{old('title')}}">
                    </div>
                    <div class="form-group">
                        <label for=""> Body:</label>
                        <wysiwyg name="body" value="{{old('body')}}"></wysiwyg>
                        {{-- <textarea name="body" class="form-control" rows="8">{{old('body')}}</textarea> --}}
                    </div>

                    {{-- <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="6LdD234UAAAAAC1KSQbePMGWO9aLQKB_J5MMTWoU"></div>
                    </div> --}}
                   
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Publish</button>
                    </div>

                    @if (count($errors))
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>                            
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
