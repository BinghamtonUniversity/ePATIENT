@extends('default.default')

@section('title', 'WAYF')

@section('content')
<div class="row">
<center><h1 style="text-align:center;">Welcome to ePATIENT!</h1></center>
<center><h3 style="text-align:center;">To Login, Please Select Your School From The List Below:</h1></center>
    <div class="col-sm-4 col-sm-offset-4">
        <ul class="list-group"> 
              @foreach(config('saml2_settings.idps') as $school => $configuration)
                <a href="/saml2/wayf/{{$school}}" class="list-group-item">{{$configuration['name']}}</a>
              @endforeach
              @if(config('app.demo.enabled'))
                <a href="/demo" class="list-group-item">Demo (Guest) Login</a>
              @endif
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="alert alert-info">
        ePATIENT is an educational EMR to aid in asynchronous interprofessional healthcare training. 
        ePATIENT allows multiple student professions, from different campuses, to simultaneously access 
        "virtual patient" records in real-time allowing them to respond to notes, orders, and comments 
        from other members of the team as clinical professionals do in real-world clinical environments. 
      </div>
    </div>
</div>
@endsection