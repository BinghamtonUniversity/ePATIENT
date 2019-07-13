@extends('default.default')

@section('title', 'Select Demo User')

@section('content')
<div class="row">
    <div class="col-sm-12" style="text-align:center;">
        <center><h3 style="text-align:center;">Please Select Your Assigned Demo User Account Below:</h1></center>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="list-group">
                    @foreach ($users as $user)
                        <a class="list-group-item" href="/demo/{{$user->id}}">{{$user->first_name}} {{$user->last_name}}: {{$user->unique_id}}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection