@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
           @include('threads._list')

           {{$threads->render()}}
        </div>
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Search
                </div>
                <div class="panel-body">
                    <form action="/threads/search" method="GET">
                        <div class="form-group">
                            <input type="text" name="q" class="form-control" placeholder="Search fpr something ...">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-default" type="submit">Search</button>
                        </div>
                    </form>

                </div>
            </div>

            @if (count($trending))
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Threading Threads
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">

                            @foreach ($trending as $trend)
                                <li class="list-group-item">
                                    <a href="{{url($trend->path)}}">
                                    {{$trend->title}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>

            @endif

        </div>
    </div>
</div>
@endsection
