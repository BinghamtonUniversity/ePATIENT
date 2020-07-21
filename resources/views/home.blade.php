@extends('default.default')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-sm-12" style="text-align:center;">
        <center><h1 style="text-align:center;">Welcome to ESTO!</h1></center>
        <center><h3 style="text-align:center;">Please Select a Simulation Toolset</h1></center>
        <div class="row">
        @foreach($apps as $app)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="panel panel-default past-due">
                    <a href="/app/{{$app['slug']}}">
                        <div class="panel-body">
                            <h3 style="margin-top:0px;">{{$app['name']}}</h3>
                            <i class="fa {{$app['icon']}} fa-10x"></i>
                        </div>
                        <div class="panel-footer">{{$app['description']}}</div>
                    </a>
                </div>
            </div>
        @endforeach   
        </div>
    </div>
</div>
@endsection