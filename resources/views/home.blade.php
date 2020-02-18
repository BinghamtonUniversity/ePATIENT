@extends('default.default')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-sm-12" style="text-align:center;">
        <center><h1 style="text-align:center;">Welcome to ePATIENT!</h1></center>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
            @if(count($teams) > 0)
                <center><h3 style="text-align:center;">Please Select a Team / Scenario</h1></center>
                <div class="list-group">
                @foreach ($teams as $team)
                    <a class="list-group-item" href="/team/{{$team->id}}">{{$team->name}}: {{$team->scenario->name}}</a>
                @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <h4 style="margin-top:0px;">You are not a member of any teams!</h4>
                    <p>Contact your professor if you feel that this is in error.</p>
                    <p>
                        Note that if you have logged in previously with a different 
                        Identity Provider (Google / Your School / etc), you may have 
                        created multiple ePATIENT accounts.  Please ensure that you
                        are logged-in with the correct account from the correct 
                        Identity Provider in order to see any relevant assignments.
                    </p>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection