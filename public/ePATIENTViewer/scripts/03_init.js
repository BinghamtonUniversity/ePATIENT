this.data.team_id = QueryStringToHash(window.location.search.substr(1)).team;

this.data.scenario_id = this.data.options.scenario_id || QueryStringToHash(window.location.search.substr(1)).scenario;
this.data.hashParams = {page:""};
this.data.vital_form = "";
this.data.admin = (this.data.options.admin == "true");
this.data.local = (this.data.options.local == "true");
if(typeof this.data.myteams == 'string'){
    this.data.myteams = [];
}
if(typeof this.data.user.unique_id == 'undefined'){
    this.data.localVars = Lockr.get('epatient');
    if(typeof this.data.localVars !== 'undefined'){
        var temp = _.find(data.users,{unique_id:this.data.localVars.user});
        if(typeof temp !== 'undefined'){
            _.extend(this.data.user,temp)
        }else{
            this.data.user.first_name = 'Guest';
            this.data.user.last_name = 'User';
            this.data.user.unique_id = "Guest1";
        }
        this.data.team_id = parseInt(this.data.localVars.team)
    }
}
