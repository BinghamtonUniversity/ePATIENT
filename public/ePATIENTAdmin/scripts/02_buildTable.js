build_table = function(resource, options) {
    var options = options || {};
    if(typeof options.add == 'undefined'){
        options.add = function(options,model){
            this.app.post(resource, $.extend(options,model.attributes), function(context, data){
                 if(data.error) {
                    this.delete();
                    this.owner.draw();
                    if (data.error.message) {
                        toastr.error(data.error.message, 'ERROR');
                    } else {
                        toastr.error(data.error, 'ERROR');
                    }
                } else if (typeof data != 'object') {
                    this.delete();
                    this.owner.draw();
                    toastr.error('Creation Failed', 'ERROR')
                } else{
                    this.set(data);
                    this.owner.draw();
                    toastr.success('', 'Successfully Added');
                }
                
                // You may want to do some additional error checking here for stuff that looks wrong
                //  If the DP returns a non-object, or data.error is returned.
            }.bind(model, this),function(e){
				toastr.error(e.statusText, 'ERROR Creating.  Refresh and try again.');
	        })
        }.bind(this, options.default)
    }
    if(typeof options.edit == 'undefined'){
        options.edit = function(options,model){
            this.app.put(resource, $.extend(options,model.attributes), function(data){
                 if(data.error) {
                    if (data.error.message) {
                        toastr.error(data.error.message, 'ERROR');
                    } else {
                        toastr.error(data.error, 'ERROR');
                    }
                    this.undo();
                    this.owner.draw();                    
                } else if (typeof data != 'object') {
                    this.undo();
                    this.owner.draw();
                    toastr.error('Edit Failed', 'ERROR')
                } else{
                    //this.set(data);
                    //this.owner.draw();
                    toastr.success('', 'Successfully Edited');
                }
            }.bind(model,this),function(e){
				toastr.error(e.statusText, 'ERROR Editing.  Refresh and try again.');
	        })
        }.bind(this, options.default)
    }
    
    if(typeof options.delete == 'undefined'){
        options.delete = function(options, model){
            this.app.delete(resource, $.extend(options,model.attributes), function(data){
                 if(data.error) {
                    if (data.error.message) {
                        toastr.error(data.error.message, 'ERROR - Please Refresh');
                    } else {
                        toastr.error(data.error, 'ERROR - Please Refresh');
                    }
                } else{
                    toastr.success('', 'Successfully Deleted');
                }
            }.bind(model),function(e){
				toastr.error(e.statusText, 'ERROR deleting.  Refresh and try again.');
	        })
        }.bind(this, options.default)
    }
    if(typeof options.autoSize == 'undefined'){
        options.autoSize = 40;
    }
    options.container =  options.container || '#table';
    options.schema =  options.schema || JSON.parse(_.find(this.options.config.forms,{name:resource}).content).fields;
    options.data =  options.data || this.data[resource];

    if(resource == 'teams'){
        if(this.data.hashParams.page !== 'team'){
            options.schema[1].options = this.data.scenarios;
            
            options.events = [
    		    {'name': 'reset', 'label': '<i class="fa fa-times"></i> Reset', callback: function(model){
                    this.app.post('scenario_log', {team_id:model.attributes.id, state:_.find(this.data.scenarios,{id:model.attributes.scenario_id}).scenario, unique_id:this.data.user.unique_id}, function(){
                        toastr.success('Team reset Successfully');
                    });
    			}.bind(this), multiEdit: false}
    		]
        }
    }    

    if(resource == 'users'){
        appcontext = this.app;
        options.events = [
            {'name': 'change_permissions', 'label': '<i class="fa fa-lock"></i> Change Permissions', callback: function(model){
                permissions_form = $().berry({
                    legend: 'Change Permissions',
                    name: 'permissions_form', 
                    inline:true,                
                    attributes: {user_id:model.attributes.id,permissions:Object.keys(model.attributes.permissions)},
                    fields: [{
                        type:'hidden',
                        value:model.attributes.id,
                        name:'user_id',
                        },{
                        label: 'Permissions',
                        type:"check_collection",
                        options:[
                            'manage_users','manage_user_permissions','manage_roles','manage_teams','manage_scenarios','manage_products','manage_prescribers','manage_solutions','manage_labs'
                        ],
                    }], actions: ['save']}).on('save', function() {
                        appcontext.post('update_permissions',permissions_form.toJSON(),function(data) {
                            this.set(data);
                            this.owner.draw();
                            toastr.success('', 'Successfully Updated Permissions');
                            permissions_form.destroy();
                        }.bind(this))
                    },model);
            }.bind(this), multiEdit: false}
        ]
    }    

    if(resource == 'scenarios'){

        options.events = [
            {'name': 'params', 'label': '<i class="fa fa-file-medical"></i> Configuration', callback: function(model){
            
                     window.open("/ar/emr/epatient/configuration?scenario="+model.attributes.id,'_blank');

                // $().berry(JSON.parse(this.forms[3].content))
                
                
                /*
                
        baseForms = ['users','roles','teams','members', 'notes','messages','scenarios','products','prescribers','solutions','labs']
                
        var attributes = model.attributes.scenario;
        
        var fields = {};
        _.each(this.forms,function(form){
            if(form.content.length && baseForms.indexOf(form.name) == -1){
                var temp = JSON.parse(form.content);
                temp.legend = form.name
                fields[form.name] = temp
            }
        })
        // for(var i in this.forms){
        //     fields[this.forms[i].name]=this.forms[i].data;
        //     if(typeof fields[this.forms[i].name] == 'string'){
        //         fields[this.forms[i].name] = JSON.parse(fields[this.data.forms[i].name]);
        //     }
        // }
        var title = 'Update Scenario - '+model.attributes.name;
        // var actions = ['cancel', 'save', 'done'];

        // if(this.data.options.type == "results"){
        //     title = "Submitted Result";
        //     actions = false;
        // }
        // if(typeof attributes.data == 'string'){
        //     attributes.data = JSON.parse(attributes.data);
        // }
        $().berry({
            attributes: attributes,
            flatten: false,
            title: title,
            renderer: 'tabs',
            fields: fields,
            name:'scenario'
        }).on('save',function(){
					var temp = this.attributes;
						temp.scenario = Berries.scenario.toJSON()
						this.set(temp)
						this.owner.options.edit(this);
						this.owner.draw();
						Berries.scenario.trigger('close')
					}.bind(model))
                
                */
			}.bind({forms:this.options.config.forms}), multiEdit: false},
		{'name': 'duplicate', 'label': '<i class="fa fa-copy"></i> Duplicate', callback: function(model){
            
            
            $().berry({
            flatten: false,
            title: "Duplicate '"+model.attributes.name+"'",
            fields: [{"label":"New Name","name":"name","required":true}],
            name:'duplicate'
        }).on('save',function(){
            if(Berries.duplicate.validate()){
                this.owner.add({name:Berries.duplicate.toJSON().name,scenario:this.attributes.scenario})
                Berries.duplicate.trigger('close');
            }
			}.bind(model))
            
            

			}.bind({forms:this.options.config.forms}), multiEdit: false}
		]
    }



    return new berryTable(options)
}       