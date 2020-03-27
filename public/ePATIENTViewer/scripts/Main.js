window.onclick = function (e) {
    if (e.target.localName == 'a' && e.target.hash && !e.target.dataset.toggle) {
        e.preventDefault();
        setHash(e.target.hash)
    }
    else if(e.target.parentElement.localName == 'a' && e.target.parentElement.hash && !e.target.parentElement.dataset.toggle){
        e.preventDefault();
        setHash(e.target.parentElement.hash)
    }
}
setHash = function(hash){
    window.location.hash = hash.substr(1);
}

Berry.collection.add('providers',_.sortBy(data.providers,'last_name'));
Berry.collection.add('products',_.sortBy(data.products,'name'));
Berry.collection.add('solutions',_.sortBy(data.solutions,'solution_name'));
Berry.collection.add('labs',data.labs);
Array.prototype.toString = function(){return this.join(', ')}

gform.collections.add('providers',_.sortBy(data.providers,'last_name'));
gform.collections.add('products',_.sortBy(data.products,'name'));
gform.collections.add('solutions',_.sortBy(data.solutions,'solution_name'));
gform.collections.add('labs',data.labs);

data.users = [
    // {label:"Guest",unique_id:'Guest1', last_name:'User', first_name:'Guest' },
    {label:"Nurse One",unique_id:'nurse1', last_name:'One', first_name:'Nurse' },
    {label:"Nurse Two",unique_id:'nurse2', last_name:'Two', first_name:'Nurse' },
    {label:"Physician Guest", unique_id:'physician', last_name:'Guest', first_name:'Physician' },
    {label:"Pharmacy Guest",unique_id:'pharmacy', last_name:'Guest', first_name:'Pharmacy' }
]

lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];

toastr.options = {
  "positionClass": "toast-top-left",
};

 save = function(state,callback){
    if(this.data.admin && this.data.scenario_id){
        updateActivity.call(this,state)

        this.app.put('scenarios', {name:state.name,id:this.data.scenario_id, scenario:this.data.scenario}, callback||function(){
            toastr.warning('Saved Configuration Successfully');
        });
    }else{
        if(this.data.team_id){
            debugger;
            state.data = _.omit(state.data,'user');
            this.app.post('activity', _.extend({team_id:this.data.team_id},state), callback||function(){

                toastr.success('Saved Team Activity Successfully');
            }.bind(this));
            // this.app.post('scenario_log', {team_id:this.data.team_id, state:state, unique_id:this.data.user.unique_id}, callback||function(){
            //     toastr.success('Saved Team status Successfully');
            // });
        }else{
            if(this.data.scenario_id && this.data.local && !this.data.team_id){
                Lockr.set('_'+this.data.scenario_id,state||{});
            }
        }
    }
}
 readHash = function(){
    this.app.click('.clear-local', function(){
        Lockr.rm('epatient');
        location.reload();

    })
    this.data.hashParams = QueryStringToHash(document.location.hash.substr(1) || '');
    this.app.update({});
    // if(typeof this.data.localVars !== 'undefined'){
    //     this.app.get('teams',{id:this.data.team_id},updateScenario);
    // }
    if(typeof this.data.hashParams.page == 'undefined'){
        modal({title:"Report to learner",content:this.data.summary_description})
    }
    if(typeof this.data.page_map[this.data.hashParams.page] !== 'undefined' && typeof this.data.page_map[this.data.hashParams.page].onload == "function"){
        this.data.page_map[this.data.hashParams.page].onload.call(this);
    }
    if(typeof this.data.hashParams.form !== 'undefined'){
        //duplicates will be created
        //not checking if found
        var temp = _.find(this.config.forms,{name:this.data.hashParams.form});
        
        if(typeof temp !== 'undefined'){
            temp = JSON.parse(temp.content);
            this.app.update({vital_form:temp.legend||temp.label});
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

            temp.data = ((this.data.page_map[temp.name] || this.data.page_map.default).attr || this.data.page_map.default.attr).call(this)
            temp.data.author = temp.data.author || this.data.user.first_name+" "+this.data.user.last_name;
			// if(_.isArray(temp.fields)){
			    temp.fields.push({"parsable": false,"type":"hidden","value":this.data.options.admin,"name":"admin"});
			// }else{
			//     temp.fields.admin = {"parsable": false,"type":"hidden","value":this.data.options.admin,"name":"admin"};
			// }
            if(typeof gform.instances[this.data.hashParams.form] !== 'undefined'){
                gform.instances[this.data.hashParams.form].destroy();
            }
            temp.horizontal = true;
            temp.inline = false;
            temp.default= {
                "horizontal": true,
                "inline":false
            }
            if(this.data.hashParams.page !== 'form'){// && !this.data.admin){
                $("#form").html((new gform(temp)).on('change',function(e){
                    $("#form").html(e.form.toString());
                }).toString())

            }else{

                new gform(temp, "#form").on('cancel', function(){
                    setHash((this.data.page_map[temp.name] || this.data.page_map.default).back)
                }.bind(this)).on('save',function(e){
                    var tempForm = e.form.get();
                    tempForm.date = tempForm.date || moment().format("MM/DD/YYYY");
                    tempForm.time = tempForm.time || moment().format("hh:mm:ss");

                    var state = ((this.data.page_map[temp.name] || this.data.page_map.default).update || this.data.page_map.default.update).call(this, this.data.scenario, tempForm);
                    
                    save.call(this,state,function(){
                        fetch_activity.call(this);

                        toastr.success('Saved Team status Successfully');
                        setHash((this.data.page_map[temp.name] || this.data.page_map.default).back)
                    });
                }.bind(this));
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
// updateScenario = function(data){
//             if(typeof data.team_scenario !== 'undefined' && data.team_scenario !== null){
//                 this.data.scenario = data.team_scenario.state || data.scenario.scenario;
//             }else{
//                 this.data.scenario = data.scenario.scenario;

//             }
        
//             this.data.scenario = scenarioInit(this.data.scenario);
    
//             this.data.scenario.name = data.name;
//             this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                
//             this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
//                 item.id = i;
//                 return item;
//             });
     
//             // var lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];
// debugger;
//             this.data.lab_types = _.map(lab_types,function(item){
//                 return {
//                     name:item,
//                     tests:_.where(this.data.labs,{category:item}),
//                     results:_.where(this.data.scenario.lab_results,{category:item})
//             }}.bind(this));
//         }

this.data.last_activity_id = null;
fetch_activity = function() {
    if(!(this.data.admin && this.data.scenario_id)){
        this.app.get('team_activity',{id:this.data.team_id,last_activity_id:this.data.last_activity_id},function(activity) {

            if(this.data.last_activity_id !== activity.last_activity_id){
                this.data.last_activity_id = activity.last_activity_id;
                _.each(activity.activity,updateActivity.bind(this));
                // this.app.update();
                chat_add_messages.call(this,activity.messages);
                notes_add_notes.call(this,activity.notes);
                // debugger;
                // debugger;
                // updateScenario.call(this.app,this.app.data);
                // this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                    
                this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
                    item.id = i;
                    return item;
                });
                this.data.lab_types = _.map(lab_types,function(item){
                    var search = {category:item};
                    if(!this.data.admin){search.enable=true;}
                    return {
                        name:item,
                        tests:_.where(this.data.labs,{category:item}),
                        results:_.where(this.data.scenario.lab_results,search)
                }}.bind(this));


                this.app.update();
            }
        });
    }
}
    
this.callback = function(){
    this.app.$el.on( "click", "[data-href]", function(e) {
        // window.location = $(e.currentTarget).data("href");
        // window.location.hash = btoa(e.currentTarget.dataset.href.substr(1));
        setHash(e.currentTarget.dataset.href);
    });
    this.app.$el.on( "click", "tr a", function(e) {
        e.stopPropagation();
        // window.location = $(e.currentTarget).data("href");
    });
        this.app.$el.on( "click", "[data-action]", function(e) {
        e.stopPropagation();
        (
            (
                this.data.page_map[this.data.hashParams.form || this.data.hashParams.page] || 
                this.data.page_map.default
            )[$(e.currentTarget).data("action")]
            ||        
            (
                this.data.page_map[ this.data.hashParams.page ||this.data.hashParams.form ] || 
                this.data.page_map.default
            )[$(e.currentTarget).data("action")] ||
            this.data.page_map.default[$(e.currentTarget).data("action")]
        ).call(this,$(e.currentTarget).data("id"),e);
            
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
            this.data.summary_description = data.scenario.summary_description;
            this.data.scenario.name = data.name;
            this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                
            this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
                item.id = i;
                return item;
            });
     
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
            // if(this.data.local){
            //     if(typeof this.data.localVars == 'undefined'){
            //         $().berry({name:"local",legend:"Initialize Scenario",fields:[
            //             {name:"team",label:"Team",type:"select",options:[
            //                 // {label:"Test", value:31},
            //                 {label:"CIT 1", value:19},
            //                 {label:"CIT 2", value:20},
            //                 {label:"CIT 3", value:21},
            //                 {label:"CIT 4", value:22},
            //                 {label:"CIT 5", value:23},
            //                 {label:"CIT 6", value:24},
            //                 {label:"CIT 7", value:25},
            //                 {label:"CIT 8", value:26},
            //                 {label:"CIT 9", value:27},
            //                 {label:"CIT 10", value:28},
            //                 {label:"CIT 11", value:29},
            //                 {label:"CIT 12", value:30},
            //             ]},
            //             {name:"user",label:"User/Role",type:"select",value_key:'unique_id',options:this.data.users}
            //         ]}).on('save',function(){
            //             Lockr.set('epatient',Berries.local.toJSON());
            //             this.data.localVars = Lockr.get('epatient');
                        
            //             if(typeof this.data.localVars !== 'undefined'){
            //                 var temp = _.find(data.users,{unique_id:this.data.localVars.user});
            //                 if(typeof temp !== 'undefined'){
            //                     _.extend(this.data.user,temp)
            //                 }else{
            //                     this.data.user.first_name = 'Guest';
            //                     this.data.user.last_name = 'User';
            //                     this.data.user.unique_id = "Guest1";
            //                 }
            //                 this.data.team_id = parseInt(this.data.localVars.team)
            //             }
            //             Berries.local.trigger('close')
            //             chat_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'});
            //             notes_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'}); 

            //         },this)
            //     }else{
            //         chat_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'});
            //         notes_init.call(this,{team_id:this.data.localVars.team,team_name:'Demo'});
            //     }
            // }
            // this.data.scenario = Lockr.get('_'+this.data.scenario_id);

            // if(typeof this.data.scenario !== 'object'){
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
                // var lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];
    debugger;
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
            
            
            // }else{
            //     //   this.data.scenario.name = data.scenario.name;
            //     this.data.scenario.lab_results = this.data.scenario.lab_results || [];
                        
            //     this.data.scenario.lab_results = _.map(this.data.scenario.lab_results,function(item,i){
            //         item.id = i;
            //         return item;
            //     })
            //     // var lab_types = ["abgs","bmp","cmpanel", "cbc","cmprofile","ck","electrolytes","lp","lfp","urinalysis","btc","csf","coagulation"];
    
            //     this.data.lab_types = _.map(lab_types,function(item){
            //         return {
            //             name:item,
            //             tests:_.where(this.data.labs,{category:item}),
            //             results:_.where(this.data.scenario.lab_results,{category:item})
            //         }}.bind(this))
    
                
            //     // chat_init.call(this,{team_id:this.data.team_id,team_name:data.name})
            //     // notes_init.call(this,{team_id:this.data.team_id,team_name:data.name})
    
            //     readHash.call(this);
            //     window.onhashchange = readHash.bind(this);
            // }
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