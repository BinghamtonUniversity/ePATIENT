@extends('default.default')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-sm-12" style="text-align:center;">
        <center><h1 style="text-align:center;">Welcome to ESTO!</h1></center>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <center><h3 style="text-align:center;">Please Select a Simulation Toolset</h1></center>
                <div class="list-group">
                    <a class="list-group-item" href="/apps/epatient">ePATIENT</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection