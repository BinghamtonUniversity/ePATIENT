
Berry.collection.add('category',[
    "Laboratory",
    "Diagnostics",
    "Dietetics",
    "Consult",
    "Procedure",
    "Supply/Device",
    "Care",
    "Specialty Department",
    "Screening/Measurements",
    "Therapy"
]);
Berry.collection.add('frequency',[
    "As directed",
    "As directed PRN",
    "Daily",
    "BID",
    "TID",
    "TID AC",
    "TID PC",
    "QID",
    "Every other day",
    "Continuous",
    "M-W-F",
    "Now",
    "STAT",
    "Once",
    "Q5MIN",
    "Q10MIN",
    "Q15MIN",
    "Q30MIN",
    "Q2H",
    "Q3H",
    "Q4H",
    "Q6H",
    "Q8H",
    "Q12H",
    "Q18H",
    "Q24H",
    "Q36H",
    "PRN Q2H",
    "PRN Q6H",
    "PRN Q8H",
    "PRN Q12H",
    "PRN QHS",
    "QAC",
    "QAC, QHS",
    "QHS",
    "QPC",
    "TODAY",
    "TOMORROW",
    "WEEKLY"
]);
Berry.collection.add('route',[
    "Arterial",
    "Both Ears (AU)",
    "Both Eyes (OU)",
    "By Mouth (PO)",
    "Epidural",
    "G-Tube",
    "Inhalation (INH)",
    "Intracardiac",
    "Intradermal",
    "Intramuscular (IM)",
    "Intrasynovial",
    "Intrathecal",
    "Intravenous (IV)",
    "Irrigation",
    "IV Piggyback (IVPB)",
    "IV Push (IVP)",
    "J Tube",
    "Left Ear (AS)",
    "Left Eye (OS)",
    "Nasal",
    "NG Tube",
    "PCA",
    "Percutanous",
    "Peritoneal",
    "Rectal",
    "Right Ear (AD)",
    "Right Eye (OD)",
    "Spinal",
    "Subcutanous (SQ)",
    "Sublingual (SL)",
    "Topical",
    "Trach",
    "Transdermal",
    "Urethral",
    "Vaginal"
]);
Berry.collection.add('providers',_.sortBy(data.providers,'last_name'));
Berry.collection.add('products',_.sortBy(data.products,'name'));
Berry.collection.add('solutions',_.sortBy(data.solutions,'solution_name'));
Berry.collection.add('labs',data.labs);
Berry.collection.add('lab_types',
[
    {'name':"abgs","label":"ABG"},
    {'name':"bmp","label":"BMP (Basic Metabolic Panel)"},
    {'name':"cmpanel","label":"Complete Metabolic Panel"},
    {'name':"cbc","label":"CBC with Differential"},
    {'name':"cmprofile","label":"CMP (Comprehensive Metabolic Profile)"},
    {'name':"ck","label":"Creatine kinase (CK) isoenzymes"},
    {'name':"electrolytes","label":"Electrolytes"},
    {'name':"lp","label":"Lipid Profile"},
    {'name':"pfp","label":"Liver Panel (Liver Function Panel)"},
    {'name':"urinalysis","label":"Urinalysis"},
    {'name':"btc","label":"Blood Type & Crossmatch"},
    {'name':"csf","label":"Cerebrospinal Fluid (CSF)"},
    {'name':"coagulation","label":"Coagulation Screen"}
]);


data.users = [
    // {label:"Guest",unique_id:'Guest1', last_name:'User', first_name:'Guest' },
    {label:"Nurse One",unique_id:'nurse1', last_name:'One', first_name:'Nurse' },
    {label:"Nurse Two",unique_id:'nurse2', last_name:'Two', first_name:'Nurse' },
    {label:"Physician Guest", unique_id:'physician', last_name:'Guest', first_name:'Physician' },
    {label:"Pharmacy Guest",unique_id:'pharmacy', last_name:'Guest', first_name:'Pharmacy' }
]


toastr.options = {
  "positionClass": "toast-top-left",
};

function save(state,callback){
    if(this.data.admin && this.data.scenario_id){
        this.app.put('scenarios', {name:state.name,id:this.data.scenario_id, scenario:state}, callback||function(){
            toastr.warning('Saved Configuration Successfully');
        });
    }else{
        if(this.data.team_id){
            this.app.post('scenario_log', {team_id:this.data.team_id, state:state, unique_id:this.data.user.unique_id}, callback||function(){
                toastr.success('Saved Team status Successfully');
            });
        }else{
            if(this.data.scenario_id && this.data.local && !this.data.team_id){
                Lockr.set('_'+this.data.scenario_id,state||{});
            }
        }
    }
}
function readHash(){
    this.app.click('.clear-local', function(){
        Lockr.rm('epatient');
        location.reload();

    })
    this.data.hashParams = QueryStringToHash(document.location.hash.substr(1) || '');
    this.app.update({});
    // if(typeof this.data.localVars !== 'undefined'){
    //     this.app.get('teams',{id:this.data.team_id},updateScenario);
    // }
    if(typeof this.data.page_map[this.data.hashParams.page] !== 'undefined' && typeof this.data.page_map[this.data.hashParams.page].onload == "function"){
        this.data.page_map[this.data.hashParams.page].onload.call(this);
    }
    if(typeof this.data.hashParams.form !== 'undefined'){
        //duplicates will be created
        //not checking if found
        var temp = _.find(this.config.forms,{name:this.data.hashParams.form});
        
        if(typeof temp !== 'undefined'){
            temp = JSON.parse(temp.content);
            this.app.update({vital_form:temp.legend});
            temp.name = this.data.hashParams.form;
            temp.autoFocus = false;
            if(this.data.admin){
                temp.fields = _.map(temp.fields,function(item, name){
                    if(item.type == 'date'){item.type = 'text';}
                    if(typeof name == 'string'){
                        item.label = name;
                    }
                    return item;
                })
            }
            if(this.data.hashParams.page !== 'form' && !this.data.admin){
                // temp.default = {enabled:false}
                // temp.inline = false;
                temp.actions = false;
                
                // temp.legend = false;

                temp.fields = _.map(temp.fields,function(item, name){
                    switch(item.type){
            			case 'custom_radio':
            			case 'radio':
                    // 	case 'select':
            				item.type = 'radio';
            				break;
            				
                    	case 'select':
                    	    item.enabled = false;
                    	    break;
                    	    
                    	case 'date':
            				item.type = 'text';
            				break;
            		}
            		if(typeof item.type == 'undefined' && (typeof item.options !== 'undefined' || typeof item.choices !== 'undefined')){item.type = 'radio';}
                    if(typeof item.label == 'undefined'){
            		item.label = item.label|| name;
                    }else{
                        // item.name = item.name|| name;
                    }
            		if(typeof item.fields !== 'undefined'){
            		    item.fields = _.map(item.fields,function(item, name){
                            switch(item.type){
                    			case 'custom_radio':
                    			case 'radio':
                    // 			case 'select':
                    				item.type = 'radio';
                    				break;
                    // 			case 'checkbox':
                    			 //   item.type = "radio"
                    			 
                    	case 'date':
            				item.type = 'text';
            				break;
                    			 
                    	case 'select':
                    	    item.enabled = false;
                    		}
                    		if(typeof item.type == 'undefined' && (typeof item.options !== 'undefined' || typeof item.choices !== 'undefined')){item.type = 'radio';}

                    if(typeof item.label == 'undefined'){
            		item.label = item.label|| name;
                    }else{
                        // item.name = item.name|| name;
                    }
                    
                    return item;
                        });
            		}
            		return item;
                });
            }
            // temp.attributes = this.data.scenario[temp.name];
            temp.attributes = (this.data.page_map[temp.name] || this.data.page_map.default).attr.call(this);
            temp.attributes.author = temp.attributes.author || this.data.user.first_name+" "+this.data.user.last_name;
  
			if(_.isArray(temp.fields)){
			    temp.fields.push({"parsable": false,"type":"hidden","value":this.data.options.admin,"name":"admin"});
			}else{
			    temp.fields.admin = {"parsable": false,"type":"hidden","value":this.data.options.admin,"name":"admin"};
			}
            if(typeof Berries[this.data.hashParams.form] !== 'undefined'){
                Berries[this.data.hashParams.form].destroy();
            }
            $('#form').berry(temp).on('cancel', function(){
                // window.history.back()
                document.location.hash = (this.data.page_map[temp.name] || this.data.page_map.default).back;
            },this).on('save',function(){
                var tempForm = Berries[this.data.hashParams.form].toJSON();
                if(typeof tempForm.date !== 'undefined'){
                    tempForm.date = tempForm.date || moment().format("MM/DD/YYYY");
                }
                var state = (this.data.page_map[temp.name] || this.data.page_map.default).update.call(this, this.data.scenario, tempForm);
                // var state = this.data.scenarioh
                // this.app.post('scenario_log', {team_id:this.data.team_id, state:state}, function(){
                //     toastr.success('Saved Successfully')
                // })
                save.call(this,state,function(){
                    toastr.success('Saved Team status Successfully');
                    document.location.hash = (this.data.page_map[temp.name] || this.data.page_map.default).back;
                });
                
                // document.location.hash = (this.data.page_map[temp.name] || this.data.page_map.default).back;


            }, this);

            if(this.data.hashParams.page !== 'form' && !this.data.admin){
                $('#form.well [type=checkbox]:not(:checked)').parent().parent().remove();
                $('#form.well .radio [type=radio]:not(:checked)').parent().parent().remove();
                $('#form.well input,#form.well textarea').attr({readonly:'readonly'});

            }
        }
    }

    $.bootstrapSortable({ applyLast: true });
    
}

scenarioInit = function(object){
    if(typeof object == 'object'){
        for(i in object){
            if(typeof object[i] == 'object'){
                object[i] = scenarioInit(object[i]);
            }else if(typeof object[i] == 'string'){
                object[i] = customRender(object[i]);
            }
        }
    }else if(typeof object == 'string'){
        object = customRender(object);
    }

   return object;
}
updateScenario = function(data){
            if(data.team_scenario !== null){
                this.data.scenario = data.team_scenario.state || data.scenario.scenario;
            }else{
                this.data.scenario = data.scenario.scenario;

            }

        
            this.data.scenario = scenarioInit(this.data.scenario);
    
    
            this.data.scenario.name = data.name;
            this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                
            this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
                item.id = i;
                return item;
            });
     
            var lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];

            this.data.lab_types = _.map(lab_types,function(item){
                return {
                    name:item,
                    tests:_.where(this.data.labs,{category:item}),
                    results:_.where(this.data.scenario.lab_results,{category:item})
            }}.bind(this));
// readHash.call(this);
        }

this.data.last_activity_id = null;
var fetch_activity = function() {
    this.app.get('team_activity',{id:this.data.team_id,last_activity_id:this.data.last_activity_id},function(activity) {
        this.data.last_activity_id = activity.last_activity_id;
        this.app.update();
        chat_add_messages.call(this,activity.messages);
        notes_add_notes.call(this,activity.notes);
    });
}
    
this.callback = function(){
    this.app.$el.on( "click", "[data-href]", function(e) {
        window.location = $(e.currentTarget).data("href");
    });
    this.app.$el.on( "click", "tr a", function(e) {
        e.stopPropagation();
        // window.location = $(e.currentTarget).data("href");
    });
        this.app.$el.on( "click", "[data-action]", function(e) {
        e.stopPropagation();
        ((this.data.page_map[this.data.hashParams.form || this.data.hashParams.page] || this.data.page_map.default)[$(e.currentTarget).data("action")]||        (this.data.page_map[ this.data.hashParams.page ||this.data.hashParams.form ] || this.data.page_map.default)[$(e.currentTarget).data("action")]).call(this,$(e.currentTarget).data("id"),e);
            
        // window.location = $(e.currentTarget).data("href");
    }.bind(this));
    
    
    // this.app.$el.on( "click", ".chat-panel .panel-heading", function(e) {
        // e.stopPropagation();
        // window.location = $(e.currentTarget).data("href");
    // });
    if(typeof this.data.team_id !== 'undefined'){
  
        this.app.get('teams',{id:this.data.team_id},function(data){
            // TJC 6/29/19 -- Don't use team_scenario is deprcated.
            // Now using team activity log to playback scenario events
            // if(data.team_scenario !== null){
            //     this.data.scenario = data.team_scenario.state || data.scenario.scenario;
            // }else{
                this.data.scenario = data.scenario.scenario;
            // }
        
            this.data.scenario = scenarioInit(this.data.scenario);
    
            this.data.scenario.name = data.name;
            this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                
            this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
                item.id = i;
                return item;
            });
     
            var lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];

            this.data.lab_types = _.map(lab_types,function(item){
                return {
                    name:item,
                    tests:_.where(this.data.labs,{category:item}),
                    results:_.where(this.data.scenario.lab_results,{category:item})
            }}.bind(this));
            readHash.call(this);
            window.onhashchange = readHash.bind(this);
        });
            
        
        chat_init.call(this);
        notes_init.call(this);
        fetch_activity.call(this);
        setInterval(fetch_activity.bind(this), 5000);


    }else{
        if((this.data.admin||this.data.local) && this.data.scenario_id){
            if(this.data.local){
                if(typeof this.data.localVars == 'undefined'){
                    $().berry({name:"local",legend:"Initialize Scenario",fields:[
                        {name:"team",label:"Team",type:"select",options:[
                            // {label:"Test", value:31},
                            {label:"CIT 1", value:19},
                            {label:"CIT 2", value:20},
                            {label:"CIT 3", value:21},
                            {label:"CIT 4", value:22},
                            {label:"CIT 5", value:23},
                            {label:"CIT 6", value:24},
                            {label:"CIT 7", value:25},
                            {label:"CIT 8", value:26},
                            {label:"CIT 9", value:27},
                            {label:"CIT 10", value:28},
                            {label:"CIT 11", value:29},
                            {label:"CIT 12", value:30},
                        ]},
                        {name:"user",label:"User/Role",type:"select",value_key:'unique_id',options:this.data.users}
                    ]}).on('save',function(){
                        Lockr.set('epatient',Berries.local.toJSON());
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
                        Berries.local.trigger('close')
                        chat_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'});
                        notes_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'}); 

                    },this)
                }else{
                    chat_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'});
                    notes_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'});
                }
            }
            // this.data.scenario = Lockr.get('_'+this.data.scenario_id);

            if(typeof this.data.scenario !== 'object'){
            this.app.get('scenarios',{id:this.data.scenario_id},function(data){
                // if(data.team_scenario !== null){
                //     this.data.scenario = data.team_scenario.state || data.scenario.scenario;
                // }else{
                //     this.data.scenario = data.scenario.scenario;
                // }
                
                this.data.scenario = data.scenario || {};
                
                if(this.data.local){
                    this.data.scenario = scenarioInit(this.data.scenario);
                }
                // this.data.scenario = JSON.parse(customRender(JSON.stringify(data.scenario || {})))
                this.data.name = data.name;
                
                // this.data.scenario.name = data.scenario.name;
                this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                        
                this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
                    item.id = i;
                    return item;
                })
                var lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];
    
                this.data.lab_types = _.map(lab_types,function(item){
                    return {
                        name:item,
                        tests:_.where(this.data.labs,{category:item}),
                        results:_.where(this.data.scenario.lab_results,{category:item})
                    }}.bind(this))
    
                
                // chat_init.call(this,{team_id:this.data.team_id,team_name:data.name})
                // notes_init.call(this,{team_id:this.data.team_id,team_name:data.name})
    
                readHash.call(this);
                window.onhashchange = readHash.bind(this);
            })
            
            
            }else{
                //   this.data.scenario.name = data.scenario.name;
                this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                        
                this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
                    item.id = i;
                    return item;
                })
                var lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];
    
                this.data.lab_types = _.map(lab_types,function(item){
                    return {
                        name:item,
                        tests:_.where(this.data.labs,{category:item}),
                        results:_.where(this.data.scenario.lab_results,{category:item})
                    }}.bind(this))
    
                
                // chat_init.call(this,{team_id:this.data.team_id,team_name:data.name})
                // notes_init.call(this,{team_id:this.data.team_id,team_name:data.name})
    
                readHash.call(this);
                window.onhashchange = readHash.bind(this);
            }
        }
    }

    // TJC 6/29/19 -- Commented this out, replacing with new inteval below
    // which calls team activity (notes, messages, and events)
    // setInterval(function(){ 
    //     if(typeof this.data.localVars !== 'undefined'){
    //         if(this.data.hashParams.page !== 'form'){
    //             this.app.get('teams',{id:this.data.team_id},updateScenario);
    //         }
    //     }
    // }.bind(this), 20000);


}