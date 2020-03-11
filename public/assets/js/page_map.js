get_page_map = function(){
return {
    "lab_form":{
        onload: function(){
            var temp = {};
            if(typeof this.data.hashParams.id !== 'undefined'){
                temp.date = this.data.scenario.lab_results[parseInt(this.data.hashParams.id)].date
                _.each(this.data.scenario.lab_results[parseInt(this.data.hashParams.id)].result,function(item){temp[item.test_name]=item.value;return temp;})
            }
    
            _.each(this.data.lab_types,function(item){
                if(item.tests.length){
                $('[name="'+item.name+'"]').berry(
                    {attributes:temp,name:item.name,fields:_.map(item.tests,function(test){test.name = test.test;return test;}),renderer:'inline'}
                    )
                }
            }.bind(this))
            
            if(this.data.admin){
                if(this.data.scenario_id){
                    $('.admin-date').berry(
                        {attributes:temp,name:'date',fields:[{label:'Lab Date',name:'date',type:'text'}],actions:[]}
                    )
                }else{
                    $('.admin-date').berry(
                        {attributes:temp,name:'date',fields:[{label:'Lab Date',name:'date',type:'date'}],actions:[]}
                    )
                }
            }
            // fields.push({name:'date'})

        },
        save: function(){
            _.each(this.data.lab_types,function(item){                
                if(item.tests.length){
                    var tests = Berries[item.name].toJSON();
                    if(!_.every(tests, _.isEmpty)){
                        var temp = _.map(tests,function(test,i){
                            return {test_name:i,value:test}
                        }.bind(this))
                        var date = moment().format("MM/DD/YYYY");
                        if(this.data.admin){
                            date = Berries.date.toJSON().date;
                        }
                        if(typeof this.data.hashParams.id !== 'undefined'){
                            this.data.scenario.lab_results[parseInt(this.data.hashParams.id)] ={category:item.name,date:date,result:temp}
                        }else{
                            this.data.scenario.lab_results.push({category:item.name,date:date,result:temp})
                        }

                        debugger;
                        updates = {category:item.name,date:date,result:temp};
                        var action = {
                            // form:this.data.hashParams.form,
                            form:"lab_results",
                            data:updates,
                            event:'create'
                        }
                        if(typeof this.data.hashParams.id !== 'undefined'){
                            action.event = "update";
                            action.form +='.'+this.data.hashParams.id;           
                        }
                        save.call(this,action,function(){
                            fetch_activity.call(this);
        
                            toastr.success('Saved Team status Successfully');
                            // document.location.hash = (this.data.page_map[temp.name] || this.data.page_map.default).back;
                        }.bind(this));
                        // return action;


                    }
                }
            }.bind(this))
            
            // this.data.scenario.lab_results = [];
            this.data.lab_types = _.map(lab_types,function(item){
                return {
                    name:item,
                    tests:_.where(this.data.labs,{category:item}),
                    results:_.where(this.data.scenario.lab_results,{category:item})
                }}.bind(this))
                
            
            this.app.update(this.data.scenario)
            // save.call(this, this.data.scenario)

            // this.app.post('scenario_log', {team_id:this.data.team_id, state:this.data.scenario}, function(){})
            document.location.hash = this.data.page_map.lab_form.back;

        },
        delete:function(id, e){
            if(this.data.admin){
                if(confirm("Are you sure you want to delete this alert?")){
                    delete this.data.scenario.lab_results[id]
                    this.data.scenario.lab_results = _.compact(this.data.scenario.lab_results);
                    this.app.update(this.data.scenario)
                    save.call(this,this.data.scenario)
                    document.location.hash = this.data.page_map.lab_form.back;
                    location.reload();
                }
            }
        },
        back:"#page=labs"
    },

    "orders":{   
        start:function(id){
            this.data.scenario.orders.order[id].status = "In Progress";
            this.app.update(this.data.scenario)
            save.call(this,this.data.scenario)

        },       
        complete:function(id){
            this.data.scenario.orders.order[id].status = "Completed"
            this.data.scenario.orders.order[id].completed_by = this.data.user.first_name+" "+this.data.user.last_name;
            this.app.update(this.data.scenario)
            save.call(this,this.data.scenario)
        },        
        back:"#page=orders"
        
    },
    "prescription_orders":{
        update:function(scenario, updates){
            var action = {
                form:'prescription_orders',
                data:updates,
                event:'create'
            }
            if(typeof this.data.hashParams.id !== 'undefined'){

                var medication_admin = this.data.scenario.prescription_orders[parseInt(this.data.hashParams.id)].medication_admin || [];
                action.data.medication_admin = medication_admin;
                action.event = "update";
                action.form +='.'+this.data.hashParams.id;           
            }

            return action;
        },        
        back:"#page=prescription_orders"
    },
    
    "medication_admin":{
        "administer":this.administer,
        "delete":function(id,e){
            // debugger;
            delete this.data.scenario.prescription_orders.order[parseInt(e.currentTarget.parentElement.parentElement.dataset.id)].medication_admin[id]
            this.data.scenario.prescription_orders.order[parseInt(e.currentTarget.parentElement.parentElement.dataset.id)].medication_admin = _.compact(this.data.scenario.prescription_orders.order[parseInt(e.currentTarget.parentElement.parentElement.dataset.id)].medication_admin);
            this.app.update(this.data.scenario)
            save.call(this,this.data.scenario)
        }
    },
    
    "alerts":{
        back:"#page=alerts"
    },
    "problems":{
        back:"#page=problems"
    },   
    "notes":{
        back:"#page=notes"
    },
    "diagnostics":{
        back:"#page=diagnostics"
    },
    "labs":{
        // attr: function(){
        //     if(typeof this.data.scenario.labs !== 'undefined'){
                
        //         return this.data.scenario.labs[parseInt(this.data.hashParams.id)] || {}
        //     }else{
        //         this.data.scenario.labs = [];
        //         return {}
        //     }
        // },
        // update:function(scenario, updates){
        //     if(typeof this.data.hashParams.id !== 'undefined'){
        //         this.data.scenario.labs[parseInt(this.data.hashParams.id)] = updates
        //     }else{
        //     this.data.scenario.labs.push(updates);
                
        //     }
        //     return this.data.scenario;
    
        // },
        // delete:function(id, e){
        //     if(this.data.admin){
        //         if(confirm("Are you sure you want to delete this lab?")){
        //             delete this.data.scenario.labs[id]
        //             this.data.scenario.labs = _.compact(this.data.scenario.labs);
        //             this.app.update(this.data.scenario)
        //             save.call(this,this.data.scenario)
        //         }
        //     }
        // },
        back:"#page=labs"
    },   
    "patient_info":{
        attr:  function(){
            return this.data.scenario.patient_info || {}
        },
        update:function(scenario, updates){
            // if(typeof this.data.hashParams.id !== 'undefined'){
            //     this.data.scenario.prescription_orders.order[parseInt(this.data.hashParams.id)] = updates
            // }else{
            // this.data.scenario.prescription_orders.order.push(updates);
                
            // }
            // return this.data.scenario;
            // var temp = 'patient_info';
            // _.reduce(temp.split('.'),function(i,map){return i[map]},this.data.scenario) 
            this.data.scenario.patient_info = updates;

            // this.data.scenario.patient_info = updates;
            return this.data.scenario;
    
        },
        back:"#page=patient_info&form=patient_info"
    },
    "pharmacist_verification":{
        attr:  function(){
            // return this.data.scenario.patient_info || {}
        },
        approve:function(id){
            this.data.scenario.prescription_orders.order[parseInt(id)].approved = 'Verified'
            this.app.update(this.data.scenario)
            save.call(this,this.data.scenario)
            // this.app.post('scenario_log', {team_id:this.data.team_id, state:this.data.scenario}, function(){})
            document.location.hash = this.data.page_map.pharmacist_verification.back;

        },
        reject:function(id){
            this.data.scenario.prescription_orders.order[parseInt(id)].approved = 'Declined'
            this.app.update(this.data.scenario)
            save.call(this,this.data.scenario)
            document.location.hash = this.data.page_map.pharmacist_verification.back;

            // this.app.post('scenario_log', {team_id:this.data.team_id, state:this.data.scenario}, function(){})
        },

        back:"#page=pharmacist_verification"
    },
    "default":{
        attr: function(){
            if(typeof this.data.scenario[this.data.hashParams.form] !== 'undefined'){
                return _.extend([],this.data.scenario[this.data.hashParams.form])
                //.reverse()
                [parseInt(this.data.hashParams.id)] || {}
            }else{
                this.data.scenario[this.data.hashParams.form] = [];
                return {}
            };
        },

        update:function(scenario, updates){
            var action = {
                form:this.data.hashParams.form,
                data:updates,
                event:'create'
            }
            if(typeof this.data.hashParams.id !== 'undefined'){
                action.event = "update";
                action.form +='.'+this.data.hashParams.id;           
            }

            return action;
        },
        delete:function(id, e){
            if(this.data.admin){
                if(confirm("Are you sure you want to delete this item?")){

                    var action = {
                        form:(e.currentTarget.dataset.form||this.data.hashParams.page)+'.'+id,
                        event:'delete'
                    }
                    save.call(this,action,function(){
                        fetch_activity.call(this);

                        toastr.success('Item Deleted Successfully');
                    })
                }
            }
        },
        "administer":this.administer,
        back:"#page=assessment"
    }
    
}
}