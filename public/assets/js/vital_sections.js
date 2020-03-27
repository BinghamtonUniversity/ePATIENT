vital_sections = [
    {
        key:'vital_signs',
        label:'Vital Signs',
        display:[
            {label:'Date',width:"200px",name:'dates',calculation:function(e){
                return gform.m('{{date}} {{time}}',e);
            }},
            // {label:'Time',name:'time'},
            // {label:'Systolic',name:'systolic'},
            // {label:'Diastolic',name:'diastolic'},
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
        ]
    },    
    {
        key:'intake_output',
        label:'Intake/Output',
        display:[
            {label:'Date',name:'date'},
            {label:'Oral',path:'intake.oral'},
            {label:'IV',path:'intake.iv'}
        ]
    },    
    {
        key:'pain',
        label:'Pain',
        display:[
            {label:'Date',name:'date'},
            {label:'Site',name:'site'},
            {label:'Quantity',name:'quantity_p', calculation:function(e){
                return parseInt(e.quantity)+'/10';
            }},
            {label:'Description',name:'description',calculation:function(e){
                return "<div><b>Aggravating Factors:</b> "+e.aggravating_factors+"</div>"+"<div><b>Quality of pain:</b> "+e.quality_of_pain+"</div>"+"<div><b>Periodicity:</b> "+e.periodicity+"</div><div><b>Duration:</b> "+e.duration+"</div>"
            }}
        ]
    },    
    {
        key:'neuro',
        label:'Neuro',
        display:[
            {label:'Date',name:'date'},
            {label:'Orientation',name:'orientation'},
            {label:'Behavioral / Emotional',name:'behavioral_emotional'},
            {label:'Strength',name:'strength_summary'},
            {label:'Pupils',name:'pupil_summary'},
            {label:'PERRLA',name:'perrla'},
            {label:'GLASGOW',name:'gscore',width:"220px",calculation:function(e){
                return gform.m('<dl class="dl-horizontal"><dt>Eye Opening Response</dt><dd>{{eye_opening_response}}</dd><dt>Motor Response</dt><dd>{{motor_response}}</dd><dt>Verbal Response</dt><dd>{{verbal_response}}</dd></dl>',e.glasgow_coma_score);
            }},
            {label:'Ramsey Scale',name:'sedation'},
            
        ]
    },    
    {
        key:'cardiac',
        label:'Cardiac',
        display:[
            {label:'Date',name:'date'},
            {label:'Heart Tones',name:'heart_tones'},
            {label:'Pulses',name:'pulses_summary'},
            {label:'Edema',name:'edema_summary'},
            {label:'Capillary Refill',name:'capillary_refill_summary'},
            {label:'Devices',name:'devices'}

        ]
    },    
    {
        key:'respiratory',
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
        ]
    },
    {
        key:'gi',
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
                debugger;
                return gform.m(`<div><b style="width:100px;display:inline-block">Diet Ordered</b>{{diet_ordered}}</div><div><b style="width:100px;display:inline-block">Consumed</b>{{amount_of_meal_consumed}}</div>`,e.general.diet);
            },width:"15%"},
            {label:'Output',name:'output_summary',calculation:function(e){
                return gform.m(`<div><b style="width:60px;display:inline-block">Stool</b>{{stool}}</div><div><b style="width:60px;display:inline-block">Emesis</b>{{emesis}}</div>`,e.general.output);
            },width:"15%"},
            {label:'Gastric tube Location',name:'gt_location',path:"general.gastric_tubes.gt_location"},
            {label:'Ostamy Location',name:'ostamy_location',path:"general.ostomy.ostomy_location"},
            {label:'Abdominal girth',name:'size',path:"general.abdominal_girth.size"}
            ]
    },    
    {
        key:'gu',
        label:'Genitourinary',
        display:[
            {label:'Date', name:'date'},
            {label:'Assessment',name:'urinary_symptoms'},
            {label:'Urine Color',name:'urine_color'},
            {label:'Urine Character',name:'urine_character'},
            {label:'Urinary Elimination',name:'urinary_elimination'},
            {label:'Amount',name:'amount'},
            
            
            
              {label:'Catheter',name:'catheter_bool',calculation:function(e){
                return (e.catheter ? "Yes" : "");
            }}
        ]
    },    
    {
        key:'musculoskeletal',
        label:'Musculoskeletal',
        display:[
            {label:'Date',name:'date'},
            {label:'Assessment',name:'musculoskeletal_symptoms'},
            {label:'Muscle Tone/Strength Summary',name:'muscle_summary'},
            {label:'Gait',name:'gait'},
            {label:'Ambulatory Device',name:'ambulatory_device'}
        ]
    },    
    {
        key:'skin',
        label:'Skin Assessment',
        display:[
            {label:'Date',name:'date'},
            {label:'Assessment',name:'skin'},
            {label:'Other',name:'other_data',calculation:function(e){
                var result = "";
                if(e.lesions.length){
                    result+= "<div><b>Lesions: </b> "+e.lesions+"</div>"
                }
                if(e.wounds.length){
                    result+= "<div><b>Wounds: </b> "+e.wounds+"</div>"
                }
                if(e.pressure_ulcers.length){
                    result+= "<div><b>Pressure Ulcers: </b> "+e.pressure_ulcers+"</div>"
                }
                return result
            }}
        ]
    },    
    {
        key:'mental',
        label:'Mental',
        display:[
            {label:'Date',name:'date'},
            {label:'Behavior/Affect',name:'behavior'},
            {label:'Coping',name:'coping'},
            {label:'Consult',name:'consult'},
            {label:'At Risk for',name:'at_risk_for'},
            {label:'Abuse Risk',name:'abuse_risk'}
        ]
    }
    
    
    
    
    
]
