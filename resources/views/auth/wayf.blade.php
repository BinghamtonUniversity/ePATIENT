@extends('default.default')

@section('title', 'WAYF')

@section('content')
<div class="row">
<center><h1 style="text-align:center;">Welcome to ePATIENT!</h1></center>
<center><h3 style="text-align:center;">To Log In, Please Select Your Identity Provider From The List Below:</h1></center>
    <div class="col-sm-4 col-sm-offset-4">
        <ul class="list-group"> 
            <a href="/idp/google/@if(isset(request()->redirect))?redirect={{request()->redirect}}@endif" class="list-group-item"><i style="margin-top: 4px;" class="fa fa-lock fa-lg fa-fw pull-right"></i><i class="fab fa-google fa-lg fa-fw"></i>&nbsp;Google Login</a>
        <?php
            $saml2_idps = config('saml2_settings.idps');
            uasort($saml2_idps, function ($a, $b) {
                return strcmp($a['name'],$b['name']);
            });
        ?>
            @foreach($saml2_idps as $school => $configuration)
                @if (in_array($school, $enabled_idps))
                    <a href="/saml2/wayf/{{$school}}@if(isset(request()->redirect))?redirect={{request()->redirect}}@endif" class="list-group-item"><i style="margin-top: 4px;" class="fa fa-lock fa-lg fa-fw pull-right"></i>{{$configuration['name']}}</a>
                @endif
            @endforeach
            @if(config('app.demo.enabled'))
                <a href="/idp/demo" class="list-group-item"><i style="margin-top: 4px;" class="fa fa-lock fa-lg fa-fw pull-right"></i>Demo (Guest) Login</a>
            @endif
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="alert alert-info">
        <p>
            ePATIENT is an educational EMR to aid in asynchronous interprofessional healthcare training. 
            ePATIENT allows multiple student professions, from different campuses, to simultaneously access 
            "virtual patient" records in real-time allowing them to respond to notes, orders, and comments 
            from other members of the team as clinical professionals do in real-world clinical environments. 
        </p>
        <p style="margin-top:20px;">
            For more information about the ePATIENT Application and how to get your campus involved, 
            please contact 
            <a href="mailto:epatient@binghamton.edu">epatient@binghamton.edu</a>.
        </p>
      </div>
    </div>
</div>
@endsection