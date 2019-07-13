@extends('default.default')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-sm-12" style="text-align:center;">
        <h3 style="text-align:center;">Please Selet a Team / Scenario</h1></center>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="list-group">
                    @foreach ($teams as $team)
                        <a class="list-group-item" href="/viewer?team={{$team->id}}">{{$team->name}}: {{$team->scenario->name}}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection