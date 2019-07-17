@extends('default.default')

@section('title', 'Logout')

@section('content')
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
    <center><h1 style="text-align:center;">ePATIENT Logout</h1></center>
</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="panel panel-default">
        <div class="panel-body">
          You are logged out of this application, but you are not logged out of the IDP
          so you can probably log back in really easily.  I guess turn off your computer
          or something?  I don't know.<br><br>
          <a href="/"><i class="fa fa-arrow-left fa-fw"></i> Return to Home</a>
        </div>
      </div>
    </div>
</div>
@endsection