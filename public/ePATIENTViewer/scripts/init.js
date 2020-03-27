// TJC -- These are now pushed down as part of the initial data load
// this.data.team_id = QueryStringToHash(window.location.search.substr(1)).team;
// this.data.scenario_id = this.data.options.scenario_id || QueryStringToHash(window.location.search.substr(1)).scenario;
// this.data.admin = (this.data.options.admin == "true");

this.data.hashParams = {page:""};
this.data.vital_form = "";
this.data.local = false;
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
this.data.messages = [];
this.data.notes = [];
this.data.newnotes = 0;
this.data.localStorageNotesVar = 'epatient_notes_read_'
// this.data.page_map = page_map
this.data.apps_pages = [
    {
        "name":"Health",
        "icon":"medkit",
        "id":0,
        "pages":[
            {name:'Patient Profile',icon:"id-badge",slug:'patient_info',form:'patient_info'},
            {name:'Overview',icon:"tachometer-alt",slug:'overview'},
            {name:'Alerts',icon:"exclamation-triangle",slug:'alerts'},
            {name:'Problem List',icon:"stethoscope",slug:'problems'},
            {name:'Assessment',icon:"thermometer-half",slug:'assessment'},
            {name:'Orders',icon:"th-list",slug:'orders'},
            // {name:'Prescription Orders',icon:"prescription-bottle",slug:'prescription_orders'},
            {name:'Med Administration',icon:"pills",slug:'medication_admin'},
            {name:'Medication Profile',icon:"file-prescription",slug:'medication_profile'},
            {name:'Diagnostic Tests',icon:"flask", slug:'diagnostics'},
            {name:'Labs',icon:"tint",slug:'labs'},
            {name:'Notes',icon:"clipboard",slug:'notes'}
        ]
    },
    {
        "name":"Pharmacy",
        "icon":"prescription",
        "id":1,
        "pages":[
            {name:'Pharmacist Verification ',icon:"clipboard-check",slug:'pharmacist_verification'}
        ]
    }
];
gform.types['barcode'] = _.extend({}, gform.types['input'],{
    satisfied: function(value){
          return (this.value.toLowerCase().trim() == this.item.help.toLowerCase().trim());
      }
})

this.data.page_map = helpers.page_map = page_map = _.keyBy(get_page_map.call(this),"slug")
this.data.vitals_sections = _.filter(page_map,function(item){ return item.display});//vital_sections;

