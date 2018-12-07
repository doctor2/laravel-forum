@extends('layouts.app')

@section('content')
<div class="container">
        <ais-index
        {{-- app-id="{{config('scout.algolia.id')}}"
        api-key="{{config('scout.algolia.key')}}"
        index-name="threads" --}}
        app-id="latency"
        api-key="3d9875e51fbd20c7754e65422f7ce5e1"
        index-name="bestbuy"
        query="{{request('q')}}"
        >
    <div class="row">
           
                <div class="col-md-8">
                    <ais-results>
                        <template slot-scope="{ result }">
                            <li>
                                <a :href="result.path">
                                    {{-- <ais-highlight :result="result" attribute-name="title"></ais-highlight> --}}
                                    <ais-highlight :result="result" attribute-name="name"></ais-highlight>
                                </a>
                                <div class="body" v-text="result.body"></div>
                            </li>
                        </template>
                    </ais-results>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Search
                        </div>
                        <div class="panel-body">
                            <ais-search-box >
                                <ais-input placeholder="Find products..." :autofocus="true"></ais-input>
                            </ais-search-box>
                        </div>
                    </div>

                    <div class="panel panel-default">
                            <div class="panel-heading">
                                Filter by channel
                            </div>
                            <div class="panel-body">
                                <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
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
</ais-index>

</div>
@endsection
