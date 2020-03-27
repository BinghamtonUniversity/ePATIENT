get_page_map = function(){
    defaultActions = [
        {name:"previous",label:"Previous",icon:"chevron-left",type:"default",condition: function(hashParams,scenario,admin){
            if(typeof scenario[hashParams.form] == "undefined"){return false}
            return hashParams.id && hashParams.form && parseInt(hashParams.id)>0;
        }},
        {name:"edit",label:"Edit",icon:"edit",type:"info",condition: function(hashParams,scenario,admin){
            return (hashParams.id && hashParams.form && admin);
        }},

        {name:"delete",label:"Delete",icon:"times",type:"danger",condition: function(hashParams,scenario,admin){
            return (hashParams.id && hashParams.form && admin);
        }},
        {name:"next",label:"Next",icon:"chevron-right",type:"default",condition: function(hashParams,scenario,admin){

            if(typeof scenario[hashParams.form] == "undefined"){return false}
            return (hashParams.id && hashParams.form && scenario[hashParams.form].length>(parseInt(hashParams.id)+1));            
        }}
    ];
return [
    {
        slug:"vital_signs",
        display:[
            {label:'Date',width:"200px",name:'dates',calculation:function(e){
                return gform.m('{{date}} {{time}}',e);
            }},
            {label:'BP',name:'blood_pressure',width:"80px",name: 'bps',calculation:function(e){
                if (isNaN(parseInt(e.blood_pressure.systolic)) || isNaN(parseInt(e.blood_pressure.diastolic))){
                    return '';
                }
                return parseInt(e.blood_pressure.systolic)+'/'+parseInt(e.blood_pressure.diastolic);
            }},
            {label:'HR',width:"80px",path:'heart_rate.beats_per_minute'},
            {label:'RR',width:"80px",path:"respiratory.breaths_per_minute"},
            {label:'Temp',width:"80px",name:'temps', calculation: function(e){
                var temp = parseInt(e.temperature.temp);
                if (temp == 0){
                    return '';
                }
                if (isNaN(temp)){
                    return '';
                }
                return temp;
            }},
            {label:'SpO2',width:"80px",name:'spo2_p', calculation: function(e){
                var temp = parseInt(e.respiratory.spo2);
                if (isNaN(temp)){
                    return '';
                }
                return temp+'%';
            }}
        ],
        actions:defaultActions,
        label:"Vital Signs",
        back:"#page=assessment"
    },
    {
        slug:"intake_output",
        display:[
            {label:'Date',name:'date'},
            {label:'Oral',path:'intake.oral'},
            {label:'IV',path:'intake.iv'}
        ],
        actions:defaultActions,
        label:"Intake/Output",
        back:"#page=assessment"
    },    
    {
        slug:"pain",
        label:'Pain',
        display:[
            {label:'Date',name:'date'},
            {label:'Site',name:'site',path:"general.site"},
            {label:'Quantity',name:'quantity_p', calculation:function(e){
                return parseInt(e.general.quantity)+'/10';
            }},
            {label:'Description',name:'description',calculation:function(e){
                return gform.m("<div><b>Aggravating Factors:</b> {{aggravating_factors}}</div><div><b>Quality of pain:</b> {{quality_of_pain}}</div><div><b>Periodicity:</b> {{periodicity}}</div><div><b>Duration:</b> {{duration}}</div>",e.general)
            }}
        ],
        actions:defaultActions,
        back:"#page=assessment"
    },    
    {
        slug:"neuro",
        label:'Neuro',
        display:[
            {label:'Date',name:'date'},
            {label:'Orientation',name:'orientation',path:"general.orientation"},
            {label:'Behavioral / Emotional',name:'behavioral_emotional',path:"general.behavioral_emotional"},
            {label:'Strength',name:'strength_summary',path:"general.strength_summary"},
            {label:'Pupils',name:'pupil_summary',path:"pupils.pupil_summary"},
            {label:'PERRLA',name:'perrla',path:"pupils.perrla"},
            {label:'GLASGOW',name:'gscore',width:"220px",calculation:function(e){
                return gform.m('<dl class="dl-horizontal"><dt>Eye Opening Response</dt><dd>{{eye_opening_response}}</dd><dt>Motor Response</dt><dd>{{motor_response}}</dd><dt>Verbal Response</dt><dd>{{verbal_response}}</dd></dl>',e.glasgow_coma_score);
            }},
            {label:'Ramsey Scale',name:'sedation',path:'ramsey_scale.sedation'},
            
        ],
        actions:defaultActions,
        back:"#page=assessment"
    },    
    {
        slug:"cardiac",
        label:'Cardiac',
        display:[
            {label:'Date',name:'date'},
            {label:'Heart Tones',name:'heart_tones',path:'general.heart_tones'},
            {label:'Pulses',name:'pulses_summary',path:'general.pulses_summary'},
            {label:'Edema',name:'edema_summary',path:'general.edema_summary'},
            {label:'Capillary Refill',name:'capillary_refill_summary',path:'general.capillary_refill_summary'},
            {label:'Devices',name:'devices',path:'general.devices'},

        ],
        actions:defaultActions,
        back:"#page=assessment"
    },
    {
        slug:"respiratory",
        label:'Respiratory',
        display:[
            {label:'Date',name:'date'},
            {label:'Assessment',name:'assess',calculation:function(e){
                return gform.m(`
                <table class="table table-bordered">
                <thead>
                <tr><td></td><td colspan=2>Anterior</td><td colspan=2>Posterior</td></tr>
                <tr><td></td><td>Left</td><td>Right</td><td>Left</td><td>Right</td></tr>
                </thead>
                <tbody>
                <tr><td>All</td><td>{{lla.All}}</td><td>{{rla.All}}</td><td>{{llp.All}}</td><td>{{rlp.All}}</td></tr>
                <tr><td>Upper Lobe</td><td>{{lla.Upper Lobe}}</td><td>{{rla.Upper Lobe}}</td><td>{{llp.Upper Lobe}}</td><td>{{rlp.Upper Lobe}}</td></tr>
                <tr><td>Mid Lobe</td><td>{{lla.Mid Lobe}}</td><td>{{rla.Mid Lobe}}</td><td>{{llp.Mid Lobe}}</td><td>{{rlp.Mid Lobe}}</td></tr>
                <tr><td>Lower Lobe</td><td>{{lla.Lower Lobe}}</td><td>{{rla.Lower Lobe}}</td><td>{{llp.Lower Lobe}}</td><td>{{rlp.Lower Lobe}}</td></tr>
                <tr><td>Axillary</td><td>{{lla.Axillary}}</td><td>{{rla.Axillary}}</td><td>{{llp.Axillary}}</td><td>{{rlp.Axillary}}</td></tr>
                </tbody>
                <table>

                `,e.breath_sounds
                )+ gform.m(`<dl class="dl-horizontal">
                <dt>Respirations</dt><dd>{{respirations}}</dd>
                <dt>Retractions</dt><dd>{{retractions}}</dd>
                <dt>Other Symptoms</dt><dd>{{other_respiratory_symptoms}}</dd>
                
                </dl>`,e.respirations)
            },width:"40%"},
            {label:'O2 Delivery method',name:'method',path:"oxygen_delivery.method"},
            {label:'Amount',name:'o2amount',path:"oxygen_delivery.o2amount"},
            {label:'Sputum',name:'sputums',calculation:function(e){
                return gform.m("<div><b>Color:</b> {{color}}</div>"+"<div><b>Consistency:</b> {{consistency}}</div>"+"<div><b>Amount:</b> {{amount}}</div>",e.sputum);
            }},
            // {label:'Airway Device',name:'airway_device'}
        ],
        actions:defaultActions,
        back:"#page=assessment"
    },
    {
        slug:"gi",
        label:'Gastrointestinal',
        display:[
            {label:'Date',name:'date'},
            {label:'Assessment',name:'summary',calculation:function(e){
                return gform.m(`<dl class="dl-horizontal">
                <dt>Abdominal Description</dt><dd>{{abdominal_description}}</dd>
                {{#abdominal_details}}
                <dt>Abdominal Details</dt><dd><i style="width:40px;display: inline-block;">LUE</i>{{LUE}}</dd><dd><i style="width:40px;display: inline-block;">RUE</i>{{RUE}}</dd><dd><i style="width:40px;display: inline-block;">LLE</i>{{LLE}}</dd><dd><i style="width:40px;display: inline-block;">RLE</i>{{RLE}}</dd>
                {{/abdominal_details}}
                <dt>Gi Symptoms</dt><dd>{{gi_symptoms}}</dd>
                {{#bowel_sounds}}
                <dt>Bowel Sounds</dt><dd><i style="width:40px;display: inline-block;">ALL</i>{{ALL}}</dd><dd><i style="width:40px;display: inline-block;">LUQ</i>{{LUQ}}</dd><dd><i style="width:40px;display: inline-block;">RUQ</i>{{RUQ}}</dd><dd><i style="width:40px;display: inline-block;">LLQ</i>{{LLQ}}</dd><dd><i style="width:40px;display: inline-block;">RLQ</i>{{RLQ}}</dd>
                {{/bowel_sounds}}
                <dt>Diet Tolerance</dt><dd>{{diet_tolerance}}</dd>
                </dl>`,e.general);
            },width:"30%"},
            {label:'Diet',name:'diet',calculation:function(e){
                return gform.m(`<div><b style="width:100px;display:inline-block">Diet Ordered</b>{{diet_ordered}}</div><div><b style="width:100px;display:inline-block">Consumed</b>{{amount_of_meal_consumed}}</div>`,e.general.diet);
            },width:"15%"},
            {label:'Output',name:'output_summary',calculation:function(e){
                return gform.m(`<div><b style="width:60px;display:inline-block">Stool</b>{{stool}}</div><div><b style="width:60px;display:inline-block">Emesis</b>{{emesis}}</div>`,e.general.output);
            },width:"15%"},
            {label:'Gastric tube Location',name:'gt_location',path:"general.gastric_tubes.gt_location"},
            {label:'Ostamy Location',name:'ostamy_location',path:"general.ostomy.ostomy_location"},
            {label:'Abdominal girth',name:'size',path:"general.abdominal_girth.size"}
            ],
            actions:defaultActions,
            back:"#page=assessment"
    },    
    {
        slug:"gu",
        label:'Genitourinary',
        display:[
            {label:'Date', name:'date'},
            {label:'Assessment',name:'urinary_symptoms',path:"general.urinary_symptoms"},
            {label:'Urine Color',name:'urine_color',path:"general.urine_color"},
            {label:'Urine Character',name:'urine_character',path:"general.urine_character"},
            {label:'Urinary Elimination',name:'urinary_elimination',path:"general.urinary_elimination"},
            {label:'Amount',name:'amount',path:"general.amount"},
              {label:'Catheter',name:'catheter_bool',calculation:function(e){
                return (e.general.catheter_enabled ? "Yes" : "No");
            }}
        ],
        actions:defaultActions,
        back:"#page=assessment"
    },    
    {
        slug:"musculoskeletal",
        label:'Musculoskeletal',
        display:[
            {label:'Date',name:'date'},
            {label:'Assessment',name:'musculoskeletal_symptoms',width:"50%;overflow:auto;",calculation:function(e){
                return '<dl class="dl-horizontal"><dt>Symptoms</dt><dd>'+e.general.musculoskeletal_symptoms+'</dd></dl>'+
                gform.m(`
                <table class="table table-bordered">
                <thead>
                <tr><td></td><td>ALL</td><td>LLE</td><td>LUE</td><td>RLE</td><td>RUE</td></tr>
                </thead>
                <tbody>
                {{#characteristic}}
                <tr><td>Characteristic</td><td>{{All}}</td><td>{{LLE}}</td><td>{{LUE}}</td><td>{{RLE}}</td><td>{{RUE}}</td></tr>
                {{/characteristic}}
                
                {{#range_of_motion}}
                <tr><td>Range of Motion</td><td>{{All}}</td><td>{{LLE}}</td><td>{{LUE}}</td><td>{{RLE}}</td><td>{{RUE}}</td></tr>
                {{/range_of_motion}}
                
                {{#motor_strength_grade}}
                <tr><td>Motor Strength Grade</td><td>{{All}}</td><td>{{LLE}}</td><td>{{LUE}}</td><td>{{RLE}}</td><td>{{RUE}}</td></tr>
                {{/motor_strength_grade}}
                </tbody>
                <table>

                `,e.general.muscle_tone_strength
                )
            }},
            {label:'Muscle Tone/Strength Summary',name:'muscle_summary',path:"general.muscle_summary"},
            {label:'Gait',name:'gait',path:"general.weight_bearing_gait.gait"},
            {label:'Ambulatory Device',name:'ambulatory_device',path:"general.weight_bearing_gait.ambulatory_device"}
        ],
        actions:defaultActions,
        back:"#page=assessment"
    },    
    {
        slug:"skin",
        label:'Skin Assessment',
        display:[
            {label:'Date',name:'date'},
            {label:'Assessment',name:'skin',path:"general.skin"},
            {label:'Other',name:'other_data',calculation:function(e){
                var result = "";
                if(e.general.lesions.length){
                    result+= "<div><b>Lesions: </b> "+e.general.lesions+"</div>"
                }
                if(e.general.wounds.length){
                    result+= "<div><b>Wounds: </b> "+e.general.wounds+"</div>"
                }
                if(e.general.pressure_ulcers.length){
                    result+= "<div><b>Pressure Ulcers: </b> "+e.general.pressure_ulcers+"</div>"
                }
                return result
            }}
        ],
        actions:defaultActions,
        back:"#page=assessment"
    },    
    {
        slug:"mental",
        label:'Mental',
        display:[
            {label:'Date',name:'date'},
            {label:'Behavior/Affect',name:'behavior',path:"general.behavior"},
            {label:'Coping',name:'coping',path:"general.coping"},
            {label:'Consult',name:'consult',path:"general.consult"},
            {label:'At Risk for',name:'at_risk_for',path:"general.at_risk_for"},
            {label:'Abuse Risk',name:'abuse_risk',path:"abuse.abuse_risk"}
        ],
        actions:defaultActions,
        back:"#page=assessment"
    },


    
    {
        slug:"lab_results",
        actions:[

            {name:"delete",label:"Delete",icon:"times",type:"danger",condition:function(hashParams,scenario,admin){
                return admin && hashParams.id;
            }},
            {name:"save",label:"Save",icon:"plus",type:"success",condition:function(hashParams,scenario){
                return true;
            }}
        ],
        onload: function(){
            var temp = {};
            if(typeof this.data.hashParams.id !== 'undefined' && typeof this.data.scenario.lab_results[parseInt(this.data.hashParams.id)] !== 'undefined'){
                temp.date = this.data.scenario.lab_results[parseInt(this.data.hashParams.id)].date
                temp.enable =  this.data.scenario.lab_results[parseInt(this.data.hashParams.id)].enable
                _.each(this.data.scenario.lab_results[parseInt(this.data.hashParams.id)].result,function(item){temp[item.test_name]=item.value;return temp;})
            }
    
            _.each(this.data.lab_types,function(item){
                if(item.tests.length){
                    new gform(
                        {clear:false,actions:[],data:temp,name:item.name,fields:_.map(item.tests,function(test){test.name = test.test;return test;}),renderer:'inline',default:{hideLabel:true,type:'text',format:{label: '{{label}}', value: '{{value}}'},target:function(){
                            return '[data-inline="'+this.name+'"]'
                        }}}
                    ,'[name="'+item.name+'"]')
                }
            }.bind(this))
            
            if(this.data.admin){
                if(this.data.scenario_id){
                    new gform(
                        {data:temp,name:'date',fields:[{label:'Lab Date',name:'date',type:'text'},{label:"Enable",type:"switch",options:[{label:"No",value:false},{label:"Yes",value:true}]}],actions:[]}
                        ,'.admin-date')
                }else{
                    new gform(
                        {data:temp,name:'date',fields:[{label:'Lab Date',name:'date',type:'date'},{label:"Enable",type:"switch",options:[{label:"No",value:false},{label:"Yes",value:true}]}],actions:[]}
                    ,'.admin-date')
                }


            }
            // fields.push({name:'date'})

        },
        save: function(){
            _.each(this.data.lab_types,function(item){                
                if(item.tests.length){
                    var tests = gform.instances[item.name].toJSON();
                    if(!_.every(tests, _.isEmpty)){
                        var temp = _.map(tests,function(test,i){
                            return {test_name:i,value:test}
                        }.bind(this))
                        var date = moment().format("MM/DD/YYYY");
                        if(this.data.admin){
                            date = gform.instances.date.toJSON().date;
                        }
                        // if(typeof this.data.hashParams.id !== 'undefined'){
                        //     this.data.scenario.lab_results[parseInt(this.data.hashParams.id)] ={category:item.name,date:date,result:temp}
                        // }else{
                        //     this.data.scenario.lab_results.push({category:item.name,date:date,result:temp})
                        // }
                        updates = {category:item.name,date:date,result:temp};
                        if(this.data.admin){
                            updates.enable = gform.instances.date.get('enable');
                        }else{
                            updates.enable = true;
                        }
                        // var action = {
                        //     form:this.data.hashParams.form,
                        //     // form:"lab_results",
                        //     data:updates,
                        //     event:'create'
                        // }
                        // if(typeof this.data.hashParams.id !== 'undefined'){
                        //     action.event = "update";
                        //     action.form +='.'+this.data.hashParams.id;           
                        // }
                        // debugger;
                        //;
                        save.call(this,
                            this.data.page_map.default.update.call(this, this.data.scenario, updates),
                            function(){
                                fetch_activity.call(this);
        
                                toastr.success('Saved Team status Successfully');
                            }.bind(this)
                        );


                    }
                }
            }.bind(this))
            
            // this.data.scenario.lab_results = [];
            // this.data.lab_types = _.map(lab_types,function(item){
            //     return {
            //         name:item,
            //         tests:_.where(this.data.labs,{category:item}),
            //         results:_.where(this.data.scenario.lab_results,{category:item})
            //     }}.bind(this))
                
            
            // this.app.update(this.data.scenario)
            // save.call(this, this.data.scenario)

            // this.app.post('scenario_log', {team_id:this.data.team_id, state:this.data.scenario}, function(){})
            setHash(this.data.page_map.lab_results.back)

        },
        label:'<i class="fa fa-flask text-muted"></i> Labs',
        back:"#page=labs"
    },
    {
        slug:"medication_profile",
        discontinue:function(id){
            this.data.scenario.prescription_orders[id].status = "Discontinued";

            save.call(this,{
                form:'prescription_orders.'+id,
                data: this.data.scenario.prescription_orders[id],
                event:'update'
            },function(){
                fetch_activity.call(this);
                toastr.success('In Progress');
                setHash("#page=medication_profile")
            });
        },
        label:'<i class="fa fa-file-prescription text-muted"></i> Medication Profile',
        actions:[
            {name:"discontinue",width:"80px",label:"Discontinue",icon:"times",type:"danger",condition:function(hashParams,scenario){
                return (scenario.prescription_orders[hashParams.id].status !== "Discontinued");
            }}
        ],filter:true,
        back:"#page=medication_profile"
    },        
    {   
        slug:"orders",
        start:function(id){
            this.data.scenario.orders[id].status = "In Progress";

            save.call(this,{
                form:'orders.'+id,
                data: this.data.scenario.orders[id],
                event:'update'
            },function(){
                fetch_activity.call(this);
                toastr.success('In Progress');
                setHash('#page=orders')
            });

        },       
        complete:function(id){
            this.data.scenario.orders[id].status = "Completed"
            this.data.scenario.orders[id].completed_by = this.data.user.first_name+" "+this.data.user.last_name;
            // this.app.update(this.data.scenario)
            // save.call(this,this.data.scenario)
            save.call(this,{
                form:'orders.'+id,
                data: this.data.scenario.orders[id],
                event:'update'
            },function(){
                fetch_activity.call(this);
                toastr.success('Completed');
                setHash('#page=orders')
            });
                
        },
        label:'<i class="fa fa-th-list text-muted"></i> Orders',
        
        actions:[
            {name:"start",label:"Start",icon:"plus",type:"success",condition: function(hashParams,scenario,admin){
                if(typeof scenario[hashParams.form] == "undefined"){return false}
                return hashParams.id && hashParams.form && scenario[hashParams.form][hashParams.id].status == "New";
            }},
            {name:"complete",label:"Complete",icon:"times",type:"warning",condition: function(hashParams,scenario,admin){
                if(typeof scenario[hashParams.form] == "undefined"){return false}
                return hashParams.id && hashParams.form && scenario[hashParams.form][hashParams.id].status == "In Progress";
            }},
            {name:"edit",label:"Edit",icon:"edit",type:"info",condition: function(hashParams,scenario,admin){
                return (hashParams.id && hashParams.form && admin);
            }},
    
            {name:"delete",label:"Delete",icon:"times",type:"danger",condition: function(hashParams,scenario,admin){
                return (hashParams.id && hashParams.form && admin);
            }}

        ],
        back:"#page=orders"
        
    },
    {
        slug:"prescription_orders",
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
        filter:true,add:true,
        actions:[
            {name:"administer",label:"Administer",icon:"syringe",condition: function(hashParams,scenario,admin){
                if(typeof scenario[hashParams.form] == "undefined"){return false}
                return (scenario.prescription_orders[hashParams.id].approved== "Verified");
            }}
            ,
            {name:"edit",label:"Edit",icon:"edit",type:"info",condition: function(hashParams,scenario,admin){
                return (hashParams.id && hashParams.form && admin);
            }},
    
            {name:"delete",label:"Delete",icon:"times",type:"danger",condition: function(hashParams,scenario,admin){
                return (hashParams.id && hashParams.form && admin);
            }}
        ],
        label:'<i class="fa fa-prescription text-muted"></i> Prescription Orders',
        back:"#page=orders"
    },
    
    {
        slug:"medication_admin",
        label:'<i class="fa fa-pills text-muted"></i> Medication Administration',
        filter:true,
        delete:function(id, e){
            if(this.data.admin){
                if(confirm("Are you sure you want to delete this item?")){
                    var action = {
                        form:'prescription_orders.'+e.currentTarget.parentElement.parentElement.dataset.id+'.medication_admin.'+e.currentTarget.dataset.id 
                        ,
                        event:'delete'
                    }
                    save.call(this,action,function(){
                        fetch_activity.call(this);

                        toastr.success('Item Deleted Successfully');
                        setHash(this.data.page_map[(e.currentTarget.dataset.form||this.data.hashParams.page)].back)
                    })
                }
            }
        }
        // "administer":this.administer,
        // "delete":function(id,e){
        //     // debugger;
        //     delete this.data.scenario.prescription_orders.order[parseInt(e.currentTarget.parentElement.parentElement.dataset.id)].medication_admin[id]
        //     this.data.scenario.prescription_orders.order[parseInt(e.currentTarget.parentElement.parentElement.dataset.id)].medication_admin = _.compact(this.data.scenario.prescription_orders.order[parseInt(e.currentTarget.parentElement.parentElement.dataset.id)].medication_admin);
        //     this.app.update(this.data.scenario)
        //     save.call(this,this.data.scenario)
        // }
    },
    
    {
        slug:"alerts",
        back:"#page=alerts",
        filter:true,
        add:true,
        label:'<i class="fa fa-exclamation-triangle text-muted"></i> Alerts',
        actions:defaultActions
    },
    {
        slug:"problems",
        back:"#page=problems",
        filter:true,
        add:true,
        label:'<i class="fa fa-stethoscope text-muted"></i> Problems',
        actions:defaultActions

    },   
    {
        slug:"notes",
        filter:true,
        add:true,
        label:'<i class="fa fa-th-list text-muted"></i> Notes',
        back:"#page=notes",
        actions:defaultActions

    },
    {
        slug:"diagnostics",
        back:"#page=diagnostics",
        label:'<i class="fa fa-flask text-muted"></i> Diagnostic Tests',
        // actions:[
        //     {label:'<i class="fa fa-edit"></i> Edit',name:"edit"}
        // ],

        filter:true,
        add:true,
        actions:defaultActions
    },
    {
        slug:"labs",
        label:'<i class="fa fa-tint text-muted"></i> Labs',
        filter:true,add:true,addLink:"#page=lab_results",
        back:"#page=labs"
    },   
    {
        slug:"patient_info",
        actions:[
            {name:"patient_edit",href:"#page=form&form=patient_info",label:"Edit",icon:"edit",type:"info",condition:function(hashParams,scenario,admin){
                return (hashParams.page == "patient_info" && admin );
            }}
        ],
        attr:  function(){
            return this.data.scenario.patient_info || {}
        },
        update:function(scenario, updates){

            var action = {
                form:'patient_info',
                data:updates,
                event:'update'
            }

            return action;

        },
        back:"#page=patient_info&form=patient_info",
        label:'<i class="fa fa-id-badge text-muted"></i> Patient Info'
    },
    {
        slug:"overview",
        label:'<i class="fa fa-tachometer-alt text-muted"></i> Overview',
        actions:[
            {name:"report_to_learner",width:"120px",label:"Report To Learner",icon:"info",type:"info",condition:function(hashParams,scenario,admin){
                return true;
            }}
        ],

        filter:true},
    {
        slug:"pharmacist_verification",
        approve:function(id){
            this.data.scenario.prescription_orders[parseInt(id)].approved = 'Verified'
            save.call(this,{
                form:'prescription_orders.'+id,
                data: this.data.scenario.prescription_orders[parseInt(id)],
                event:'update'
            },function(){
                fetch_activity.call(this);
                toastr.success('Verified');
                setHash("#page=pharmacist_verification")
            });

        },
        reject:function(id){
            this.data.scenario.prescription_orders[parseInt(id)].approved = 'Declined'
            save.call(this,{
                form:'prescription_orders.'+id,
                data: this.data.scenario.prescription_orders[parseInt(id)],
                event:'update'
            },function(){
                fetch_activity.call(this);
                toastr.success('Declined');
                setHash("#page=pharmacist_verification")
            });
        },

        edit:function(){
            setHash('#page=form&form=prescription_orders&id='+this.data.hashParams.id)
        },
        actions:[

            {name:"edit",label:"Edit",icon:"edit",type:"info",condition: function(hashParams,scenario,admin){
                return (hashParams.id && hashParams.form && admin);
            }},
    
            {name:"delete",label:"Delete",icon:"times",type:"danger",condition: function(hashParams,scenario,admin){
                return (hashParams.id && hashParams.form && admin);
            }},
            {name:"approve",label:"Verify",icon:"check",condition: function(hashParams,scenario,admin){
                if(typeof scenario[hashParams.form] == "undefined"){return false}
                return hashParams.id && hashParams.form;
            }},
            {name:"reject",label:"Decline",icon:"times",type:"warning",condition: function(hashParams,scenario,admin){
                if(typeof scenario[hashParams.form] == "undefined"){return false}
                return hashParams.id && hashParams.form;
            }}
        ],
        back:"#page=pharmacist_verification"
    },

    {
        slug:"assessment",
        label:'<i class="fa fa-thermometer-half text-muted"></i> Assessments',
        back:"#page=assessment"
    },
    {
        slug:"default",
        label:'<i class="fa fa-thermometer-half text-muted"></i> Assessments',
        previous:function(){
            setHash('#page='+this.data.hashParams.page+'&form='+this.data.hashParams.form+'&id='+(parseInt(this.data.hashParams.id)-1))
        },
        next:function(){
            setHash('#page='+this.data.hashParams.page+'&form='+this.data.hashParams.form+'&id='+(parseInt(this.data.hashParams.id)+1))
        },
        edit:function(){
            setHash('#page=form&form='+this.data.hashParams.page+'&id='+this.data.hashParams.id)
        },
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
            // debugger;
            var action = {
                form:(this.data.hashParams.form|| this.data.hashParams.page),
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
            debugger;
            if(this.data.admin){
                if(confirm("Are you sure you want to delete this item?")){

                    var action = {
                        form:(e.currentTarget.dataset.form||this.data.hashParams.page)+'.'+id,
                        event:'delete'
                    }
                    save.call(this,action,function(){
                        fetch_activity.call(this);

                        toastr.success('Item Deleted Successfully');
                        setHash(this.data.page_map[(e.currentTarget.dataset.form||this.data.hashParams.page)].back)
                    })
                }
            }
        },
        "report_to_learner":function(){
            modal({title:"Report to learner",content:this.data.summary_description})

        },
        "administer": function(id){
            var order = this.data.scenario.prescription_orders[id];
            var fields = [
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
                            setHash("#page=medication_admin")
                        });
                    e.form.trigger('close')
                    
                }
            }.bind(this)).on('cancel', function(e){
                e.form.trigger('close')
        })
            .on('change:patient', function(e){
                    $(e.field.el).toggleClass('has-success', e.form.validate());
            })
            .on('change:prescription', function(item){
                    this.fields.prescription.set(item.value)
                    
                    var field = this.findByID(item.id);
                    this.performValidate(field, item.value)
                    field.self.toggleClass('has-success', field.valid);
            }).modal()
        },
        back:"#page=assessment"
    }
    
]
}