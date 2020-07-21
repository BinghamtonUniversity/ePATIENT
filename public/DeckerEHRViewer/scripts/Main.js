//Load Scenarios
this.data.scenarios = _.sortBy(_.map(this.data.scenarios, function(item){
    try{
		if(typeof item.data == 'string') {
			item.data = JSON.parse(item.data)
		}
		item.rank = parseInt(item.name) || 1000;
	}catch(e){ console.log(e); }
	return item
}), 'rank')


//Load templates
_.each(this.data.templates, function(template){
	try{
		templates[template.name] = Hogan.compile(template.data);
	}catch(e){ console.log(e); }
})

//Load forms
forms = {};
_.each(this.data.forms, function(form){
    forms[form.name] = (typeof form.data == 'string') ? JSON.parse(form.data) : form.data;
});

pageSession = {};

this.callback = function() {
	load.call(this, false);
	window.onhashchange = load.bind(this, false);

    //process Submission
    this.app.click('#submit', function(e){
        e.preventDefault();
        if(hashParams.patient){
        	$().berry({name:'modal', legend: "Submit", fields: {"Your Name": {required:true}}}).on('save', function(){
        		Berries.modal.trigger('close');
        		this.app.post('submit', {user:this.toJSON().your_name, name: hashParams['patient'], data: JSON.stringify(Lockr.get(hashParams.patient))})
        	},this)
        }else{
        	$().berry({actions:['cancel'],legend: "Submit", fields: {"warning":{type:"raw", label:false, value:"Please Choose a Patient first." }}})
        }
    })

}

/* Pages Script */
pages = {
	medical_admin_record: function() {
		$('[data-order-text]').on('click', function(e){
			var fields = [
				{label: 'Patient/Date of Birth', type: 'barcode', name:'patient', required: true, help: e.currentTarget.dataset.name},
				{label: 'Prescription', type: 'barcode', name:'prescription', required: true, help: e.currentTarget.dataset.orderValidation || e.currentTarget.dataset.orderText},
				{label: 'Initials', required:true},
				{label: 'Confirm', value: getNodeIndex(e.currentTarget.parentNode), type: 'hidden', required: true},
				{name: 'type', type: 'hidden', value:e.currentTarget.dataset.type}
			]
			if(e.currentTarget.dataset.im){
				fields.push({label: "Injection Location", required:true, options:[
					"right deltoid",
					"left deltoid",
					"right ventralgluteal",
					"left ventralgluteal",
					"right vastus lateralis",
					"left vastus lateralis",
					"right dorsal gluteal",
					"left dorsal gluteal"
				]})
			}
			if(e.currentTarget.dataset.sq){
				fields.push({label: "Injection Location", required:true, options:[
					"back of right arm",
					"back of left arm",
					"abdomen - right side",
					"abdomen - left side",
					"other"
				]})
				fields.push({label: 'Other', show:{ "matches": {
					"name": "insulin_administration_location",
					"value": "other"
				}}})
			}
			if(e.currentTarget.dataset.insulin){
				fields.push({label: "Chemstrip value",help:"units", required:true})
				fields.push({label: "Regular Insulin Sliding Scale",help:"units", type:'select', required:true, min: 0, max: 12, default:'Patient Refused'})
				fields.push({label: "Insulin administration location", required:true, options:[
					"back of right arm",
					"back of left arm",
					"abdomen - right side",
					"abdomen - left side",
					"other"
				]})				
				fields.push({label: 'Other', show:{ "matches": {
					"name": "insulin_administration_location",
					"value": "other"
				}}})

			}
			$().berry({name:'validate',legend: 'Confirmation', fields: fields}).on('save', function() {
				if( this.validate() ) {
					var values = this.toJSON();
					session.medication_admin_record = session.medication_admin_record || {scheduled:[], prn:[]};
					var position = parseInt(values.confirm, 10);
					var length = session.medication_admin_record[values.type].length;
					if(position > length) {
						for(var i = length; i < position; i++) {			
							session.medication_admin_record[values.type][i] = {has_been_administered: false};
						}
					}
					session.medication_admin_record[values.type][position-1] = {has_been_administered: true, initials: values.initials, time: moment().format('LT')};
					session.medication_admin_record[values.type][position-1].notes = this.toJSON();
					store();
					this.trigger('saved');
				}
			})
			.on('saved', function(){load(true)})
			.on('change:patient', function(item){
					pageSession.patient = item.value;
					this.fields.patient.setValue(item.value)
					this.performValidate(this.findByID(item.id), item.value)
					this.findByID(item.id).self.toggleClass('has-success', this.findByID(item.id).valid);
					
			})
			.on('change:prescription', function(item){
					this.fields.prescription.setValue(item.value)
					this.performValidate(this.findByID(item.id), item.value)
					this.findByID(item.id).self.toggleClass('has-success', this.findByID(item.id).valid);
			})
			if(pageSession.patient){
				Berries.validate.fields.patient.setValue(pageSession.patient);
			}
		})

	},
	form: function(){
		var fields = {};
		var atts = {};
		var renderer = 'base';

		if(typeof forms[hashParams.form] !== 'undefined'){
			fields = forms[hashParams.form].fields;
			var stored = Lockr.get(hashParams.patient) || {};
			atts = $.extend(_.find(this.data.scenarios,{id:parseInt(hashParams['patient'])}).data[hashParams.form],stored[hashParams.form]);

			//if multiple instance type form
			if(_.keys(fields).length == 1 && fields[_.keys(fields)[0]].multiple){
				fields[_.keys(fields)[0]].multiple = {duplicate: false}
			}

			$('.header .col-sm-3').html(forms[hashParams.form].legend || 'FORM')
		}

		$('#form').berry({actions:['save', 'cancel', 'clear'] ,renderer: renderer, flatten: false, attributes: atts, fields: fields}).on('save', function() {
				var stored = Lockr.get(hashParams.patient) || {};

				//if multiple instance type form
				if(_.keys(this.options.fields).length == 1 && fields[_.keys(this.options.fields)[0]].multiple && !fields[_.keys(this.options.fields)[0]].multiple.duplicate){
					stored[hashParams.form] = stored[hashParams.form] || [];
					stored[hashParams.form].push(this.toJSON());
				}else{
					stored[hashParams.form] = this.toJSON();
				}

				Lockr.set(hashParams.patient, stored);
		}).on('cancel', function() {
			window.history.back();
		}).on('clear', function() {
			var stored = Lockr.get(hashParams.patient) || {};
			stored[hashParams.form] = null;
			Lockr.set(hashParams.patient, stored);

			window.location.reload();
		});
	}
};

/* Utils Script */
load = function (disableScroll) {
	hashParams = QueryStringToHash(document.location.hash.substr(1) || '')
	var page = hashParams['page'] || 'main';
	if(pageSession.page !== page){
		pageSession = {page:page};
	}
	if(hashParams['patient']){
		var info = Lockr.get(hashParams['patient']) || {};
		session = info.session || {};
		data = _.find(this.data.scenarios,{id:parseInt(hashParams['patient'])}).data
	}else{
		session = {};
		data = {"scenarios": this.data.scenarios};
	}

	if(hashParams['page'] == 'patient_lab'){data.lab = _.find(data.labs.items,{name:hashParams['lab']}) }
	if(hashParams['page'] == 'patient_procedure'){data.procedure = _.find(data.procedures.procedure,{procedure:hashParams['procedure']}) }

	$('#container').html(render(page, {data: $.extend(true, {}, data, session), hash: hashParams, session: session}));
	if(!disableScroll)$('html, body').scrollTop(0);

	if(typeof pages[page] !== 'undefined') {
		pages[page].call(this);
	}
}.bind(this)
	
function render(template, data){
	if(typeof templates[template] !== 'undefined' && templates[template].length !== 0 ){
 	    return customRender(templates[template].render(data, templates), data);
	}else{
		alert('not found:'+template);
	}
}



function customRender(content, scope) {
  scope = scope||{};
  var myRegexp = /\[(.*?)\]/g;
  var match = myRegexp.exec(content);
  var response = JSON.parse( JSON.stringify( content ) );
  var temp;

  while (match != null) {
  	try{
  		var format = 'L';
  		if(match[1].indexOf(':')>=0){
  			var parts = match[1].split(':');
  			format = parts[1];
  			match[1] = parts[0];
  		}
  		match[1] = match[1].replace(/Admission/gi, '{{patient_information.admitted_on}}')
  		// .replace(/DOB/gi, '{{patient_information.date_of_birth}}');
  		.replace(/\{\{&gt;/gi, '{{>');
  		match[1] = customRender(Hogan.compile(match[1]).render(scope.data, templates), scope);	

  		var converted = {};
  		if(match[1].indexOf('&')>=0){
  			 var parts = match[1].split('&');
  			 converted = moment(Date.past(parts[0].substr(0,parts[0].length-1))).subtract(parts[1].substr(4), 'years').format();
  		}
  		else{
  			converted = Date.create(match[1]);
  		}
        if((typeof converted == "string" || converted instanceof Date) && converted !== "Invalid date" && converted !== "Invalid Date"){
           temp = moment(converted).format(format);
           if(converted == "Invalid Date"){
           	temp = match[1];
           }
        }else{
           temp = match[1];
        }
	}catch(e){}

    response = response.replace(match[0], temp || match[0]);
    match = myRegexp.exec(content);
  }
	return response;
}



function getNodeIndex(node) {
  var index = 0;
  while (node = node.previousSibling) {
    if (node.nodeType != 3 || !/^\s*$/.test(node.data)) {
      index++;
    }
  }
  return index;
}		

var hashParams
var QueryStringToHash = function(query) {
  var query_string = {};
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    pair[0] = decodeURIComponent(pair[0]);
    pair[1] = decodeURIComponent((pair[1] || "").split('+').join(' '));
      // If first entry with this name
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = pair[1];
      // If second entry with this name
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [ query_string[pair[0]], pair[1] ];
      query_string[pair[0]] = arr;
      // If third or later entry with this name
    } else {
      query_string[pair[0]].push(pair[1]);
    }
  } 
  return query_string;
};

// Storage
function store(){
	var stored = Lockr.get(hashParams.patient) || {};
	stored.session = session;
	Lockr.set(hashParams.patient, stored);
}
clear = function(){
	var stored = Lockr.get(hashParams.patient) || {};
	stored.session = {};
	for(var i in forms){
		stored[i]= {};
	}
	Lockr.set(hashParams.patient, stored);
	document.location.reload();
}




/* EHR Berry Extensions */
templates["berry__addons"] = new Hogan.Template({code: function (c,p,i) { var t=this;t.b(i=i||"");t.b("<!-- <span class=\"help-inline\"> ");t.b(t.t(t.f("help",c,p,0)));t.b("</span> -->");t.b("\n" + i);t.b("<span class=\"font-xs text-danger\" style=\"display:block;\"></span>");return t.fl(); },partials: {}, subs: {  }});
templates["berry__label"] = new Hogan.Template({code: function (c,p,i) { var t=this;t.b(i=i||"");if(!t.s(t.f("hideLabel",c,p,1),c,p,1,0,0,"")){t.b("	");if(t.s(t.f("label",c,p,1),c,p,0,26,327,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<label for=\"");t.b(t.v(t.f("guid",c,p,0)));t.b("\" ");if(t.s(t.f("inline",c,p,1),c,p,0,59,82,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("style=\"text-align:left\"");});c.pop();}t.b(" class=\"control-label col-md-");if(t.s(t.f("inline",c,p,1),c,p,0,133,135,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("12");});c.pop();}if(!t.s(t.f("inline",c,p,1),c,p,1,0,0,"")){t.b("4");};t.b("\">");t.b("\n" + i);t.b("  ");t.b(t.t(t.f("label",c,p,0)));t.b(":");if(t.s(t.f("required",c,p,1),c,p,0,199,233,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<span class=\"text-danger\">*</span>");});c.pop();}t.b("\n" + i);t.b("  <div style=\"font-weight:normal;font-style: italic;\"> ");t.b(t.t(t.f("help",c,p,0)));t.b("</div>");t.b("\n" + i);t.b("</label>");});c.pop();}t.b("\n" + i);};return t.fl(); },partials: {}, subs: {  }});
templates["berry_check_collection"] = new Hogan.Template({code: function (c,p,i) { var t=this;t.b(i=i||"");t.b("<div class=\"row clearfix form-group ");t.b(t.v(t.f("modifiers",c,p,0)));t.b(" ");if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,73,136,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("dupable\" data-min=\"");t.b(t.v(t.d("multiple.min",c,p,0)));t.b("\" data-max=\"");t.b(t.v(t.d("multiple.max",c,p,0)));});c.pop();}t.b("\" name=\"");t.b(t.v(t.f("name",c,p,0)));t.b("\" data-type=\"");t.b(t.v(t.f("type",c,p,0)));t.b("\">");t.b("\n" + i);t.b(t.rp("<berry__label0",c,p,"	"));if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,242,392,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	<div class=\"duplicate add btn btn-default\"><i class=\"fa fa-plus\"></i></div>");t.b("\n" + i);t.b("	<div class=\"btn btn-default remove\"><i class=\"fa fa-minus\"></i></div>");t.b("\n" + i);});c.pop();}if(t.s(t.f("label",c,p,1),c,p,0,427,522,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	");if(t.s(t.f("inline",c,p,1),c,p,0,440,463,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"col-md-12\">");});c.pop();}t.b("\n" + i);t.b("	");if(!t.s(t.f("inline",c,p,1),c,p,1,0,0,"")){t.b("<div class=\"col-md-8\">");};t.b("\n" + i);});c.pop();}if(!t.s(t.f("label",c,p,1),c,p,1,0,0,"")){t.b("\n" + i);t.b("	<div class=\"col-md-4\"></div>");t.b("\n" + i);t.b("	<div class=\"col-md-8\">");t.b("\n" + i);};t.b("		");if(t.s(t.f("pre",c,p,1),c,p,0,622,695,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group\"><span class=\"input-group-addon\">");t.b(t.t(t.f("pre",c,p,0)));t.b("</span>");});c.pop();}t.b("\n" + i);t.b("		");if(!t.s(t.f("pre",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("post",c,p,1),c,p,0,723,748,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group\">");});c.pop();}};t.b("\n" + i);t.b("<div class=\"row\" style=\"margin-top:1px\">");t.b("\n" + i);if(t.s(t.f("options",c,p,1),c,p,0,824,1173,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("\n" + i);t.b("<div class=\"checkbox col-md-4\">");t.b("\n");t.b("\n");t.b("\n" + i);t.b("						<label class=\"");t.b(t.v(t.f("alt-display",c,p,0)));t.b("\">");t.b("\n" + i);t.b("							<input name=\"");t.b(t.v(t.f("value",c,p,0)));t.b("\" type=\"checkbox\" ");if(!t.s(t.f("isEnabled",c,p,1),c,p,1,0,0,"")){t.b("readonly");};t.b(" ");if(t.s(t.f("selected",c,p,1),c,p,0,995,1010,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("checked=checked");});c.pop();}t.b(">");if(t.s(t.f("container",c,p,1),c,p,0,1038,1125,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<");t.b(t.v(t.f("container",c,p,0)));t.b(" style=\"position:relative;display:inline-block\">");t.b(t.v(t.f("label",c,p,0)));t.b("</");t.b(t.v(t.f("container",c,p,0)));t.b(">");});c.pop();}t.b("\n" + i);t.b("						</label>");t.b("\n" + i);t.b("</div>");t.b("\n");t.b("\n" + i);});c.pop();}t.b("									</div>");t.b("\n");t.b("\n" + i);t.b("		");if(t.s(t.f("post",c,p,1),c,p,0,1214,1269,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<span class=\"input-group-addon\">");t.b(t.t(t.f("post",c,p,0)));t.b("</span></div>");});c.pop();}t.b("\n" + i);t.b("		");if(!t.s(t.f("post",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("pre",c,p,1),c,p,0,1298,1304,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("</div>");});c.pop();}};t.b("\n" + i);t.b(t.rp("<berry__addons1",c,p,"		"));t.b("	</div>");t.b("\n" + i);t.b("</div>");return t.fl(); },partials: {"<berry__label0":{name:"berry__label", partials: {}, subs: {  }},"<berry__addons1":{name:"berry__addons", partials: {}, subs: {  }}}, subs: {  }});
templates["berry_qrcode"] = new Hogan.Template({code: function (c,p,i) { var t=this;t.b(i=i||"");t.b("<div class=\"row clearfix form-group ");t.b(t.v(t.f("modifiers",c,p,0)));t.b(" ");if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,73,136,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("dupable\" data-min=\"");t.b(t.v(t.d("multiple.min",c,p,0)));t.b("\" data-max=\"");t.b(t.v(t.d("multiple.max",c,p,0)));});c.pop();}t.b("\" name=\"");t.b(t.v(t.f("name",c,p,0)));t.b("\" data-type=\"file\">");t.b("\n" + i);t.b(t.rp("<berry__label0",c,p,"	"));if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,238,388,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	<div class=\"duplicate add btn btn-default\"><i class=\"fa fa-plus\"></i></div>");t.b("\n" + i);t.b("	<div class=\"btn btn-default remove\"><i class=\"fa fa-minus\"></i></div>");t.b("\n" + i);});c.pop();}if(t.s(t.f("label",c,p,1),c,p,0,423,518,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	");if(t.s(t.f("inline",c,p,1),c,p,0,436,459,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"col-md-12\">");});c.pop();}t.b("\n" + i);t.b("	");if(!t.s(t.f("inline",c,p,1),c,p,1,0,0,"")){t.b("<div class=\"col-md-8\">");};t.b("\n" + i);});c.pop();}if(!t.s(t.f("label",c,p,1),c,p,1,0,0,"")){t.b("	<div class=\"col-md-12\">");t.b("\n" + i);};t.b("		");if(t.s(t.f("pre",c,p,1),c,p,0,588,671,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group col-xs-12\"><span class=\"input-group-addon\">");t.b(t.t(t.f("pre",c,p,0)));t.b("</span>");});c.pop();}t.b("\n" + i);t.b("    ");if(!t.s(t.f("pre",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("post",c,p,1),c,p,0,701,726,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group\">");});c.pop();}};t.b("\n" + i);t.b("		");if(!t.s(t.f("success",c,p,1),c,p,1,0,0,"")){t.b("<input ");if(!t.s(t.f("autocomplete",c,p,1),c,p,1,0,0,"")){t.b("autocomplete=\"off\"");};t.b(" class=\"form-control\" ");if(!t.s(t.f("isEnabled",c,p,1),c,p,1,0,0,"")){t.b("readonly");};t.b(" ");if(t.s(t.f("maxLength",c,p,1),c,p,0,890,915,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("maxlength=\"");t.b(t.v(t.f("maxLength",c,p,0)));t.b("\"");});c.pop();}if(t.s(t.f("min",c,p,1),c,p,0,937,951,"{{ }}")){t.rs(c,p,function(c,p,t){t.b(" min=\"");t.b(t.v(t.f("min",c,p,0)));t.b("\"");});c.pop();}if(t.s(t.f("max",c,p,1),c,p,0,967,981,"{{ }}")){t.rs(c,p,function(c,p,t){t.b(" max=\"");t.b(t.v(t.f("max",c,p,0)));t.b("\"");});c.pop();}t.b(" placeholder=\"");t.b(t.v(t.f("placeholder",c,p,0)));t.b("\" type=\"file\" name=\"");t.b(t.v(t.f("name",c,p,0)));t.b("\" id=\"");t.b(t.v(t.f("name",c,p,0)));t.b("\" value=\"");t.b(t.v(t.f("value",c,p,0)));t.b("\" />");t.b("\n" + i);t.b("    ");if(t.s(t.f("post",c,p,1),c,p,0,1096,1151,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<span class=\"input-group-addon\">");t.b(t.t(t.f("post",c,p,0)));t.b("</span></div>");});c.pop();}t.b("\n" + i);t.b("    ");if(!t.s(t.f("post",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("pre",c,p,1),c,p,0,1182,1188,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("</div>");});c.pop();}};t.b("\n" + i);t.b(t.rp("<berry__addons1",c,p,"		"));t.b("		");};if(t.s(t.f("success",c,p,1),c,p,0,1253,1348,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"text-success\" style=\"padding-top: 7px;\">");t.b(t.v(t.f("value",c,p,0)));t.b(" <i class=\"fa fa-check\"></i></div>");});c.pop();}t.b("\n" + i);t.b("	</div>");t.b("\n" + i);t.b("</div>");return t.fl(); },partials: {"<berry__label0":{name:"berry__label", partials: {}, subs: {  }},"<berry__addons1":{name:"berry__addons", partials: {}, subs: {  }}}, subs: {  }});
templates["berry_radio_collection"] = new Hogan.Template({code: function (c,p,i) { var t=this;t.b(i=i||"");t.b("<div class=\"row clearfix form-group ");t.b(t.v(t.f("modifiers",c,p,0)));t.b(" ");if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,73,136,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("dupable\" data-min=\"");t.b(t.v(t.d("multiple.min",c,p,0)));t.b("\" data-max=\"");t.b(t.v(t.d("multiple.max",c,p,0)));});c.pop();}t.b("\" name=\"");t.b(t.v(t.f("name",c,p,0)));t.b("\" data-type=\"");t.b(t.v(t.f("type",c,p,0)));t.b("\">");t.b("\n" + i);t.b(t.rp("<berry__label0",c,p,"	"));if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,242,392,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	<div class=\"duplicate add btn btn-default\"><i class=\"fa fa-plus\"></i></div>");t.b("\n" + i);t.b("	<div class=\"btn btn-default remove\"><i class=\"fa fa-minus\"></i></div>");t.b("\n" + i);});c.pop();}if(t.s(t.f("label",c,p,1),c,p,0,427,522,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	");if(t.s(t.f("inline",c,p,1),c,p,0,440,463,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"col-md-12\">");});c.pop();}t.b("\n" + i);t.b("	");if(!t.s(t.f("inline",c,p,1),c,p,1,0,0,"")){t.b("<div class=\"col-md-8\">");};t.b("\n" + i);});c.pop();}if(!t.s(t.f("label",c,p,1),c,p,1,0,0,"")){t.b("	<div class=\"col-md-12\">");t.b("\n" + i);};t.b("		");if(t.s(t.f("pre",c,p,1),c,p,0,592,665,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group\"><span class=\"input-group-addon\">");t.b(t.t(t.f("pre",c,p,0)));t.b("</span>");});c.pop();}t.b("\n" + i);t.b("		");if(!t.s(t.f("pre",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("post",c,p,1),c,p,0,693,718,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group\">");});c.pop();}};t.b("\n");t.b("\n" + i);t.b("			<table class=\"table table-striped\" >");t.b("\n" + i);t.b("				<thead>");t.b("\n" + i);t.b("				<tr>");t.b("\n" + i);t.b("					<th>&nbsp;</th>");t.b("\n" + i);if(t.s(t.f("options",c,p,1),c,p,0,836,892,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("					<th style=\"text-align:center\">");t.b(t.v(t.f("label",c,p,0)));t.b("</th>");t.b("\n" + i);});c.pop();}t.b("				</tr>");t.b("\n");t.b("\n" + i);t.b("				</thead>");t.b("\n" + i);t.b("				<tbody>");t.b("\n" + i);if(t.s(t.f("labels",c,p,1),c,p,0,956,1375,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("				<tr>");t.b("\n" + i);t.b("					<td>");t.b("\n" + i);t.b("						<label ");if(t.s(t.f("inline",c,p,1),c,p,0,1000,1020,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("class=\"radio-inline\"");});c.pop();}t.b(">");t.b("\n" + i);t.b("							");t.b(t.t(t.f("name",c,p,0)));if(!t.s(t.f("name",c,p,1),c,p,1,0,0,"")){t.b("&nbsp;");};t.b("\n" + i);t.b("						</label>");t.b("\n" + i);t.b("					</td>");t.b("\n");t.b("\n" + i);if(t.s(t.f("options",c,p,1),c,p,0,1119,1349,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("					<td style=\"text-align:center\">");t.b("\n" + i);t.b("						<input data-label=\"");t.b(t.v(t.f("label",c,p,0)));t.b("\" name=\"");t.b(t.v(t.d("item.name",c,p,0)));t.b(t.v(t.f("name",c,p,0)));t.b("\" value=\"");t.b(t.v(t.f("value",c,p,0)));t.b("\" ");if(!t.s(t.f("isEnabled",c,p,1),c,p,1,0,0,"")){t.b("readonly");};t.b(" type=\"radio\" ");if(t.s(t.f("selected",c,p,1),c,p,0,1302,1317,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("checked=checked");});c.pop();}t.b(" >");t.b("\n" + i);t.b("					</td>");t.b("\n" + i);});c.pop();}t.b("				</tr>");t.b("\n" + i);});c.pop();}t.b("		</tbody>");t.b("\n" + i);t.b("			</table>");t.b("\n");t.b("\n" + i);t.b("		");if(t.s(t.f("post",c,p,1),c,p,0,1422,1477,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<span class=\"input-group-addon\">");t.b(t.t(t.f("post",c,p,0)));t.b("</span></div>");});c.pop();}t.b("\n" + i);t.b("		");if(!t.s(t.f("post",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("pre",c,p,1),c,p,0,1506,1512,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("</div>");});c.pop();}};t.b("\n" + i);t.b(t.rp("<berry__addons1",c,p,"		"));t.b("	</div>");t.b("\n" + i);t.b("</div>");return t.fl(); },partials: {"<berry__label0":{name:"berry__label", partials: {}, subs: {  }},"<berry__addons1":{name:"berry__addons", partials: {}, subs: {  }}}, subs: {  }});
templates["berry_scale"] = new Hogan.Template({code: function (c,p,i) { var t=this;t.b(i=i||"");t.b("<div class=\"row clearfix form-group ");t.b(t.v(t.f("modifiers",c,p,0)));t.b(" ");if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,73,136,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("dupable\" data-min=\"");t.b(t.v(t.d("multiple.min",c,p,0)));t.b("\" data-max=\"");t.b(t.v(t.d("multiple.max",c,p,0)));});c.pop();}t.b("\" name=\"");t.b(t.v(t.f("name",c,p,0)));t.b("\" data-type=\"");t.b(t.v(t.f("type",c,p,0)));t.b("\">");t.b("\n" + i);t.b(t.rp("<berry__label0",c,p,"	"));if(t.s(t.d("multiple.duplicate",c,p,1),c,p,0,242,392,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	<div class=\"duplicate add btn btn-default\"><i class=\"fa fa-plus\"></i></div>");t.b("\n" + i);t.b("	<div class=\"btn btn-default remove\"><i class=\"fa fa-minus\"></i></div>");t.b("\n" + i);});c.pop();}if(t.s(t.f("label",c,p,1),c,p,0,427,522,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	");if(t.s(t.f("inline",c,p,1),c,p,0,440,463,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"col-md-12\">");});c.pop();}t.b("\n" + i);t.b("	");if(!t.s(t.f("inline",c,p,1),c,p,1,0,0,"")){t.b("<div class=\"col-md-8\">");};t.b("\n" + i);});c.pop();}if(!t.s(t.f("label",c,p,1),c,p,1,0,0,"")){t.b("	<div class=\"col-md-12\">");t.b("\n" + i);};t.b("		");if(t.s(t.f("pre",c,p,1),c,p,0,592,665,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group\"><span class=\"input-group-addon\">");t.b(t.t(t.f("pre",c,p,0)));t.b("</span>");});c.pop();}t.b("\n" + i);t.b("		");if(!t.s(t.f("pre",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("post",c,p,1),c,p,0,693,718,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<div class=\"input-group\">");});c.pop();}};t.b("\n");t.b("\n" + i);t.b("			<table class=\"table table-striped\">");t.b("\n" + i);t.b("				<thead>");t.b("\n" + i);t.b("				<tr>");t.b("\n" + i);t.b("					<th></th>");t.b("\n" + i);if(t.s(t.f("options",c,p,1),c,p,0,829,859,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("					<th>");t.b(t.v(t.f("label",c,p,0)));t.b("</th>");t.b("\n" + i);});c.pop();}t.b("					<th></th>");t.b("\n" + i);t.b("				</tr>");t.b("\n");t.b("\n" + i);t.b("				</thead>");t.b("\n" + i);t.b("				<tbody>");t.b("\n" + i);t.b("				<tr>");t.b("\n" + i);t.b("					<td>");t.b("\n" + i);t.b("						");t.b(t.t(t.f("low",c,p,0)));t.b("\n" + i);t.b("					</td>");t.b("\n");t.b("\n" + i);if(t.s(t.f("options",c,p,1),c,p,0,987,1178,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("					<td>");t.b("\n" + i);t.b("						<input data-label=\"");t.b(t.v(t.f("label",c,p,0)));t.b("\" name=\"");t.b(t.v(t.f("name",c,p,0)));t.b("\" value=\"");t.b(t.v(t.f("value",c,p,0)));t.b("\" ");if(!t.s(t.f("isEnabled",c,p,1),c,p,1,0,0,"")){t.b("readonly");};t.b(" type=\"radio\" ");if(t.s(t.f("selected",c,p,1),c,p,0,1131,1146,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("checked=checked");});c.pop();}t.b(" >");t.b("\n" + i);t.b("					</td>");t.b("\n" + i);});c.pop();}t.b("					<td>");t.b("\n" + i);t.b("						");t.b(t.t(t.f("high",c,p,0)));t.b("\n" + i);t.b("					</td>");t.b("\n" + i);t.b("				</tr>");t.b("\n");t.b("\n" + i);t.b("		</tbody>");t.b("\n" + i);t.b("			</table>");t.b("\n");t.b("\n" + i);t.b("		");if(t.s(t.f("post",c,p,1),c,p,0,1275,1330,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("<span class=\"input-group-addon\">");t.b(t.t(t.f("post",c,p,0)));t.b("</span></div>");});c.pop();}t.b("\n" + i);t.b("		");if(!t.s(t.f("post",c,p,1),c,p,1,0,0,"")){if(t.s(t.f("pre",c,p,1),c,p,0,1359,1365,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("</div>");});c.pop();}};t.b("\n" + i);t.b(t.rp("<berry__addons1",c,p,"		"));t.b("	</div>");t.b("\n" + i);t.b("</div>");return t.fl(); },partials: {"<berry__label0":{name:"berry__label", partials: {}, subs: {  }},"<berry__addons1":{name:"berry__addons", partials: {}, subs: {  }}}, subs: {  }});
templates["berry_tabs"] = new Hogan.Template({code: function (c,p,i) { var t=this;t.b(i=i||"");t.b("<ul class=\"nav nav-tabs\" style=\"margin-bottom:15px\">");t.b("\n" + i);if(t.s(t.f("sectionList",c,p,1),c,p,0,70,144,"{{ }}")){t.rs(c,p,function(c,p,t){t.b("	<li>");t.b("\n" + i);t.b("		<a href=\"#tab");t.b(t.v(t.f("index",c,p,0)));t.b("\" data-toggle=\"tab\">");t.b(t.t(t.f("text",c,p,0)));t.b("</a>");t.b("\n" + i);t.b("	</li>");t.b("\n" + i);});c.pop();}t.b("</ul>");return t.fl(); },partials: {}, subs: {  }});


Berry.register({ type: 'radio_collection',
	acceptObject: true,
	create: function() {
		this.options = Berry.processOpts.call(this.owner, this.item, this).options;
		return Berry.render('berry_' + (this.elType || this.type), this);
	},
	setup: function() {
		this.$el = this.self.find('[type=radio]');
		this.$el.off();
		if(this.onchange !== undefined) {
			this.on('change', this.onchange);
		}
		this.$el.change($.proxy(function(){this.trigger('change');}, this));
	},
	getValue: function() {
		var values = {}
		for(var label in this.labels){
			var selected = this.self.find('[name="'+this.name+this.labels[label].name+'"][type="radio"]:checked').data('label');
			for(var i in this.item.options) {
				if(this.item.options[i].label == selected) {
					values[this.labels[label].name] = this.item.options[i].value;
					// return this.item.options[i].value;
				}
			}
		}
		return values;
	},
	setValue: function(value) {
		this.value = value;
		for(var i in this.labels){
			this.self.find('[name="'+this.name+this.labels[i].name+'"][value="' + this.value[this.labels[i].name] + '"]').prop('checked', true);
		}
	},
	// set: function(value){
	// 	if(this.value != value) {
	// 		//this.value = value;
	// 		this.setValue(value);
	// 		this.trigger('change');
	// 	}
	// },
	displayAs: function() {
		for(var i in this.item.options) {
			if(this.item.options[i].value == this.lastSaved) {
				return this.item.options[i].label;
			}
		}
	},
	focus: function(){
		this.self.find('[name='+this.labels[0].name+'][type="radio"]:checked').focus();
	}
});

Berry.register({ type: 'scale',
	create: function() {
		this.options = Berry.processOpts.call(this.owner, this.item, this).options;
		return Berry.render('berry_' + (this.elType || this.type), this);
	},
	setup: function() {
		this.$el = this.self.find('[type=radio]');
		this.$el.off();
		if(this.onchange !== undefined) {
			this.on('change', this.onchange);
		}
		this.$el.change($.proxy(function(){this.trigger('change');}, this));
	},
	getValue: function() {
		var selected = this.self.find('[type="radio"]:checked').data('label');
		for(var i in this.item.options) {
			if(this.item.options[i].label == selected) {
				return this.item.options[i].value;
			}
		}
	},
	setValue: function(value) {
		this.value = value;
		this.self.find('[value="' + this.value + '"]').prop('checked', true);
	},
	displayAs: function() {
		for(var i in this.item.options) {
			if(this.item.options[i].value == this.lastSaved) {
				return this.item.options[i].label;
			}
		}
	},
	focus: function(){
		this.self.find('[type="radio"]:checked').focus();
	}
});

Berry.register({ type: 'check_collection',
	defaults: {container: 'span', acceptObject: true},
	create: function() {
		this.options = Berry.processOpts.call(this.owner, this.item, this).options;

		this.checkStatus(this.value);
		return Berry.render('berry_check_collection', this);
	},
	checkStatus: function(value){
		if(value === true || value === "true" || value === 1 || value === "1" || value === "on" || value == this.truestate){
			this.value = true;
		}else{
			this.value = false;
		}
	},
	setup: function() {
		this.$el = this.self.find('[type=checkbox]');
		this.$el.off();
		if(this.onchange !== undefined) {
			this.on('change', this.onchange);
		}
		this.$el.change($.proxy(function(){this.trigger('change');},this));
	},
	getValue: function() {

		var values = [];
		for(var opt in this.options){
			if(this.self.find('[name="'+this.options[opt].value+'"][type="checkbox"]').is(':checked')){
				// values[this.options[opt].value] = (this.truestate || true);
				values.push(this.options[opt].value);
			}else{
				if(typeof this.falsestate !== 'undefined') {
					// values[this.options[opt].value] = this.falsestate;
				}else{
					// values[this.options[opt].value] = false;
				}
			}
			
		}
		return values;
	},
	setValue: function(value) {
		// this.checkStatus(value);
		// this.$el.prop('checked', this.value);
		// this.value = value;
		// debugger;
		this.value = value;
			this.self.find('[type="checkbox"]').prop('checked', false);
		for(var i in this.value){
			this.self.find('[name="'+this.value[i]+'"][type="checkbox"]').prop('checked', true);
		}
	},
	displayAs: function() {
		for(var i in this.item.options) {
			if(this.item.options[i].value == this.lastSaved) {
				return this.item.options[i].name;
			}
		}
	},
	focus: function(){
		//this.$el.focus();
		this.self.find('[type=checkbox]:first').focus();
	},
	satisfied: function(){
		return this.$el.is(':checked');
	},
});

Berry.register({
	type: 'barcode',
	defaults: { elType: 'text' },
	update: function(item, silent) {
		if(typeof item === 'object') {
			$.extend(this.item, item);
		}
		$.extend(this, this.item);
		this.setValue(this.value);
		this.render();
		this.setup();
		if(!silent) {
			this.trigger('change');
		}
	},
	satisfied: function(){
		return (this.value.toLowerCase() == this.help.toLowerCase());
	}
});


Berry.btn.clear = {
	label: 'Clear',
	icon:'times',
	id: 'berry-clear',
	modifier: 'danger pull-left',
	click: function() {
		this.trigger('clear');
	}
};