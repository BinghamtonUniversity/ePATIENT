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
this.data.vitals_sections = vital_sections;
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
            {name:'Prescription Orders',icon:"prescription-bottle",slug:'prescription_orders'},
            {name:'Med Administration',icon:"pills",slug:'medication_admin'},
            {name:'Diagnostic Tests',icon:"flask", slug:'diagnostics'},
            {name:'Labs',icon:"tint",slug:'labs'},
            {name:'Notes',icon:"clipboard",slug:'notes'},
        ]
    },
    {
        "name":"Pharmacy",
        "icon":"prescription",
        "id":1,
        "pages":[
            {name:'Medication Profile',icon:"file-prescription",slug:'medication_profile'},
            {name:'Pharmacist Verification ',icon:"clipboard-check",slug:'pharmacist_verification'}
        ]
    }
];
gform.types['barcode'] = _.extend({}, gform.types['input'],{
    satisfied: function(value){
          return (this.value.toLowerCase().trim() == this.item.help.toLowerCase().trim());
      }
})
this.administer = function(id){
    var order = this.data.scenario.prescription_orders[id];
    var fields = [
        //  {label: 'Prescription', type: 'barcode', name:'prescription', required: true, help:order.medication },

        // {label: 'Patient MR#', type: 'barcode', name:'patient', required: true, help:this.data.scenario.patient_info.medical_record_number},

        {label: 'Patient/Date of Birth', type: 'barcode', name:'patient', required: true, help: this.data.scenario.patient_info.first_name+' '+this.data.scenario.patient_info.last_name+' '+this.data.scenario.patient_info.dob},
        // {label: 'Initials', required:true},
        {name: 'id', type: 'hidden', value:id}

    ]
    if(this.data.admin){
        fields.push({label:'Date',type:"date",value:moment().format("MM/DD/YYYY HH:mm")})
        fields.push({label:'Time',type:"time",value:moment().format("hh:mm A")})
        fields.push({label:'Administered By',value:this.data.user.first_name+" "+this.data.user.last_name})
    }
        new gform({name:'validate',legend: 'Confirmation', fields: fields}).on('save', function(e) {
        if( e.form.validate() ) {
            var order = this.data.scenario.prescription_orders[parseInt(e.form.get('id'))]
            order.medication_admin = order.medication_admin || [];
            if(!this.data.admin){
                order.medication_admin.push({
                    date:moment().format("MM/DD/YYYY HH:mm"), 
                    time:moment().format("hh:mm A"),
                    administered_by:this.data.user.first_name+" "+this.data.user.last_name
                })
            }else{
                    order.medication_admin.push(_.pick(e.form.get(),'date','time','administered_by'))
            }
                order.medication_admin = _.sortBy(order.medication_admin, 'date')
                save.call(this,{
                    form:'prescription_orders.'+parseInt(e.form.get('id')),
                    data: order,
                    event:'update'
                },function(){
                    fetch_activity.call(this);
                    toastr.success('Administered');
                    document.location.hash = "page=prescription_orders";
                });

                // this.app.update(this.data.scenario)
                // save.call(this,this.data.scenario)
            e.form.trigger('close')
            
        }
    }.bind(this))
    .on('change:patient', function(e){
        // ;
        // debugger;
//        gform.validateItem(true,e.field)

            $(e.field.el).toggleClass('has-success', e.form.validate());

            // this.fields.patient.set(item.value)
            
            // var field = this.findByID(item.id);
            // this.performValidate(field, item.value)
            // field.self.toggleClass('has-success', field.valid);
    })
    .on('change:prescription', function(item){
            this.fields.prescription.set(item.value)
            
            var field = this.findByID(item.id);
            this.performValidate(field, item.value)
            field.self.toggleClass('has-success', field.valid);
    }).modal()



}


this.data.page_map = page_map = get_page_map.call(this)