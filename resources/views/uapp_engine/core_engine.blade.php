<!-- Begin core_engine -->
<div class="hidden-xs"> </div>

<script src='/assets/js/grapheneAppEngine.js'></script> 
<script src='/assets/js/vendor/moment.js'></script>

<script src='/assets/js/vendor/jquery.min.js'></script>
<script src="/assets/js/vendor/bootstrap.min.js"></script>
<script src='/assets/js/vendor/hogan.min.js'></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>-->
<script src='/assets/js/vendor/summernote.min.js'></script>

<script src='/assets/js/vendor/berry.full.js'></script> 
<script src='/assets/js/vendor/moment_datepicker.js'></script>

<script src='/assets/js/vendor/bootstrap.full.berry.js'></script> 
<script src='/assets/js/vendor/berrytables.full.js'></script> 

<script src='/assets/js/vendor/bootstrap.cobler.js'></script> 
<script src="/assets/js/vendor/lodash.min.js"></script>
<script>_.findWhere = _.find; _.where = _.filter;_.pluck = _.map;_.contains = _.includes;</script>
<script src='/assets/js/vendor/gform_bootstrap.min.js'></script> 
<script src='/assets/js/vendor/GrapheneDataGrid.min.js'></script> 

<script src='/assets/js/vendor/ractive.min.js'></script>    
<script src='/assets/js/vendor/lockr.min.js'></script>    
<script src='/assets/js/vendor/toastr.min.js'></script> 
<script src="/assets/js/vendor/ace/ace.js" charset="utf-8"></script>
<script src="/assets/js/vendor/jquery.nivo.slider.min.js" charset="utf-8"></script>


<script src="/assets/js/vendor/sortable.js"></script>
<script src='/assets/js/cob/cob.js'></script>
<script src='/assets/js/cob/content.cob.js'></script>
<script src='/assets/js/cob/image.cob.js'></script>

<script src='/assets/js/cob/uapp.cob.js'></script>
<script src='/assets/js/cob/flow.cob.js'></script>
<script src='/assets/js/cob/links.cob.js'></script>
<script src='/assets/js/templates/widget.js'></script>
<script src='/assets/js/lib.js'></script> 
<script src='https://drvic10k.github.io/bootstrap-sortable/Scripts/bootstrap-sortable.js'></script>	
<!-- Included Scripts -->
<!-- End Included Scripts-->
<script >
	resource_type = "app";
    var resource_id = "{{ $name }}";
	var config = {"sections":[[{"title":"View","app_id":"{{ $name }}","widgetType":"uApp","container":true}]],"layout":4};
    var mobile_order = [];
    var apps = [{!! $app_data !!}];
	_.findWhere = _.find;
	_.where = _.filter;
	
	group_id = 0;
	
	group_admin = false;
	if(group_admin){
		$('body').addClass('admin');
	}
		
    appMode = true;
    if(resource_type == 'app'){
        var fields = JSON.parse(apps[0].app.code.forms[1].content || '{}').fields;
        if(typeof fields !== 'undefined' && fields.length){
            $('#edit_instance').show();
            $('#edit_instance').on('click', function(){
                editUserOptions(cb.collections[0].getItems()[0])    
            })
        }
    }

    $('.'+resource_type+resource_id).addClass('active');

	$('#group'+group_id).addClass('in');

	layouts = [
			{
				value: '0',
				classes: 'bu-3-6-3',
				title: 'Wide Middle (3-6-3)',
				label: '<i title="Wide Middle" class="bu-3-6-3"></i>',
				template: '<div class="col-md-3 col-sm-4 cobler_container"></div><div class="col-sm-8 col-md-6 cobler_container"></div><div class="col-md-3 col-sm-12 cobler_container"></div>'
			},
			{
				value: '1',
				classes: 'bu-6-3-3',
				title: 'Wide Left (6-3-3)',
				label: '<i title="Wide Left" class="bu-6-3-3"></i>',
				template: '<div class="col-md-6 col-sm-8 cobler_container"></div><div class="col-md-3 col-sm-4 cobler_container"></div><div class="col-md-3 col-sm-12 cobler_container"></div>'
			},
			{
				value: '2',
				classes: 'bu-4-4-4',
				title: 'Even (4-4-4)',
				label: '<i title="Even" class="bu-4-4-4"></i>',
				template: '<div class="col-md-4 col-sm-6 cobler_container"></div><div class="col-md-4 col-sm-6 cobler_container"></div><div class="col-md-4 col-sm-12 cobler_container"></div>'
			},
			{
				value: '3',
				classes: 'bu-6-6',
				title: 'Split (6-6)',
				label: '<i title="Split" class="bu-6-6"></i>',
				template: '<div class="col-sm-6 cobler_container"></div><div class="col-sm-6 cobler_container"></div>'
			},
			{
				value: '4',
				classes: 'bu-12-12',
				title: 'Full Page (12)',
				label: '<i title="Full" class="bu-12-12"></i>',
				template: '<div class="col-lg-12 cobler_container"></div>'
			},
			{
				value: '5',
				classes: 'bu-oddshape',
				title: 'Odd Shape',
				label: '<i title="Odd Split" class="bu-oddshape"></i>',
				template: '<div class="col-sm-4 cobler_container"></div><div class="col-sm-8"><div class="row"><div class="col-sm-12 cobler_container"></div></div><div class="row"><div class="col-sm-6 cobler_container"></div><div class="col-sm-6 cobler_container"></div></div></div>'
			},
			{
				value: '6',
				classes: 'bu-doubledown',
				title: 'Double Down (12 6-6 12)',
				label: '<i title="Double Down" class="bu-doubledown"></i>',
				template: '<div class="col-sm-12"><div class="row"><div class="col-sm-12 cobler_container"></div></div><div class="row"> <div class="col-sm-6 cobler_container"></div><div class="col-sm-6 cobler_container"></div></div><div class="row"><div class="col-sm-12 cobler_container"></div></div></div>'
			},
			{
				value: '7',
				classes: 'bu-3-6-3',
				title: 'Middle Only (_-6-_)',
				label: '<i title="Middle Only" class="bu-3-6-3"></i>',
				template: '<div class="col-lg-offset-3 col-md-offset-2  col-lg-6 col-md-8 col-sm-12 cobler_container"></div></div>'
			}
		];



	function layout() {
		var berry = $().berry({
			legend: 'Change Layout', 
			inline:false,
			fields:[
				{
					label:'Layout',
					name:'layout',
					label_key:'title',
					classes: layouts[config.layout||0].classes, 
					choices: layouts, 
					value: config.layout
				}
			]
		}).on('save',function(){
			$.ajax({
				url:'/api/pages/@isset($id){{$id}}@endisset',
				data:{"content": JSON.stringify($.extend({}, config,{layout:parseInt(this.toJSON().layout)}))} ,
				method:'PUT',
				success: function(data) {
					config = data.content;
					location.reload()
				}
			})
		});
	}

	function manage(){
		switch(resource_type){
			case "page":
			load(false);
			break;			
			case "flow":
			document.location = "/admin/workflowinstances/"+resource_id
			break;
		}
	}

	function load(status){
		$('body').toggleClass('editor', !status)
		if(typeof cb !== 'undefined'){
			cb.destroy();
			delete cb;
		}
		var template = 'widgets_container'
		var target = 'widget';
		if(!status){
			template = 'widgets_edit_container';
		}


		var data = config.sections || [[]];// [[{"title":"This is the title","app_id":1,"widgetType":"uApp"}]];

		if(window.getComputedStyle($('.hidden-xs')[0]).display == 'none'){
				tempdata = [].concat.apply([],config.sections || [[]])

				if(typeof mobile_order !== 'undefined'){
					tempdata = _.sortBy(tempdata, function(o) {
						return mobile_order.indexOf(o.guid);
					})
				}
				data = [tempdata];
				$('#page_layout').html(layouts[4].template);

		}else{
			if(typeof config.layout == 'string' && config.layout.length > 1){
				$('#page_layout').html(config.layout);
			}else{
				$('#page_layout').html(layouts[config.layout || 0].template);
			}

		}

		cb = new Cobler({ disabled: status, targets: document.getElementsByClassName('cobler_container'),itemContainer: template,itemTarget:target, items:data})
		if(!status){
			if(document.getElementById('sortableList') != null){
				cb.addSource(document.getElementById('sortableList'));
			}
			if("@isset($slug){{$slug}}@endisset" == 'dashboard'){
				var save = function(){
					$.post('/api/dashboard',{"config":JSON.stringify({"sections":cb.toJSON({editor: true})}) },function(data){
					// config = JSON.parse(data.config);
					config = data.config;
				})}
			}else{
				var save = function(){$.ajax({
					url:'/api/pages/@isset($id){{$id}}@endisset',
					data:{"content": JSON.stringify( {"sections":cb.toJSON({editor: true})  ,"layout":config.layout})} ,
					method:'PUT',
					success: function(data){
						config = data.content;
					}
				})}
			}
			cb.on('moved',save);
			cb.on('reorder', save);
			cb.on('remove', save);
			cb.on('change',save);
			cb.on('manage',function(item){
				$().berry({name: 'modal', attributes: item.get(), legend: 'Display Settings',flatten:true, fields:[
					{label: 'Device', name: 'device', type: 'select', value:'widget', choices: [{label: 'All', value:'widget'}, {label: 'Desktop Only', value:'hidden-xs hidden-sm'},{label: 'Tablet and Desktop', value:'hidden-xs'} ,{label: 'Tablet and Phone', value:'hidden-md hidden-lg'} ,{label: 'Phone Only', value:'visible-xs-block'} ] },
					{label: 'Enclose in a Container?', name:'container', type: 'checkbox'},
					{label: 'Display Title Bar?', name:'titlebar', type: 'checkbox', show:{matches:{name:'container',value:true}}},
					{label: 'Allow Minimization?', name: 'enable_min', type: 'checkbox', show:{matches:{name:'container',value:true}}}, //show:!item.get().no_minimize},
					{label:' Start Minimized?', name:'collapsed',type:'checkbox', show:{matches:{name:'enable_min',value:true}},parse:'show'},
					{label: 'Limit to Group', name: 'limit', type: 'checkbox', show:  {test: function(){return group_composites.length >0;}} },
						{legend: 'Groups', 'show': {
									matches: {
										name: 'limit',
										value: true
									}
								},fields:[
							{label: false, multiple:{duplicate:true}, toArray:true, name: 'group', fields:[
									{label: false, name: 'ids', type: 'select', options: group_composites}
								]
							}]},
				]})
				.on('save',function(){
					this.container.update(Berries.modal.toJSON(),this)
					Berries.modal.trigger('saved');
					save();
				},item)
			})

			// cb.on('min', function(item){
			// 	item.container.update({collapsed:!item.get().collapsed},item)
			// 	save();
			// })

		}else{
			cb.on('min', function(item){
				$(item.container.elementOf(item)).find('.collapsible').toggle(400 , $.proxy(function() {
					item.set({collapsed: $(item.container.elementOf(item)).find('.widget').toggleClass('cob-collapsed').hasClass('cob-collapsed') });
					Lockr.set(item.get().guid,{collapsed:item.get().collapsed});
				},item) );
			})
			cb.on('user_edit', editUserOptions)
		}
	}

function editMobileOrder(){
	var tempdata = [].concat.apply([],config.sections || [[]])
	if(typeof mobile_order !== 'undefined'){
		tempdata = _.sortBy(tempdata, function(o) {
			return mobile_order.indexOf(o.guid);
		})
	}



	mymodal = modal({title: "Mobile Layout", content: templates.listing.render({widgets:tempdata} ), footer: '<div class="btn btn-success save-mobile">Save</div>'})

	Sortable.create($(mymodal.ref).find('.modal-content ol')[0], {draggable:'li'})
		
	$('.save-mobile').on('click', function(e){
		new_mobile_order = _.map($(mymodal.ref).find('.modal-content ol').find('li'), function(item){return item.dataset.guid});
		$.ajax(
			{
				url: '/api/pages/' + resource_id, 
				data: {'mobile_order': JSON.stringify(new_mobile_order)},
				method: 'PUT',
				success: function(e){
					mobile_order = new_mobile_order;
					mymodal.ref.modal('hide');
				}
		});
	})
}


	function editUserOptions(item){
		if(typeof item.bae !== 'undefined'){
			new gform($.extend(true, {legend:'Edit Options',name:'user_options', data: item.bae.data.user.options, actions:[{type:'cancel'},{type:"save"}]},JSON.parse(_.find(item.bae.options.config.forms,{name:'User Options'}).content))).modal().on('save', function(e){
				var url = '/api/apps/instances/'+item.bae.config.app_instance_id+'/user_options';
				if(typeof item.bae.data.user.id !== 'undefined') {
					$.ajax({
						type: 'POST',
						dataType : 'json',
						url:url,
						data: {'options': e.form.get()},
						success:function(app,data){
							app.update({user:{options:data.options}});
							this.trigger('close');
							app.trigger('options');
							app.alert({title:'Options Updated Successfully'})
						}.bind(e.form,item.bae.app),
						error:function(data){
								toastr.error(data.statusText, 'An error occured updating options')
						}
					})
				}else{
					item.bae.app.update({user:{options:e.form.get()}});
					Lockr.set(url, {'options': e.form.get()})
					e.form.trigger('close');
					toastr.success('', 'Options Updated Successfully');
				}
			}).on('cancel',function(e){
				e.form.trigger('close')
			})
		}
	}
	function addWidget(e) { 
		cb.collections[0].addItem(e); 	
	}



 window.onload = load.bind(null,true);

	$('body').keydown(function(event) {
		switch(event.keyCode) {
			case 27://escape
					cb.deactivate();
				break;
		}
	});


	// Automatically cancel unfinished ajax requests 
	// when the user navigates elsewhere.
	(function($) {
		var xhrPool = [];
		$(document).ajaxSend(function(e, jqXHR, options){
			xhrPool.push(jqXHR);
		});
		$(document).ajaxComplete(function(e, jqXHR, options) {
			xhrPool = $.grep(xhrPool, function(x){return x!=jqXHR});
		});
		var abort = function() {
			$.each(xhrPool, function(idx, jqXHR) {
				jqXHR.abort();
			});
		};

		var oldbeforeunload = window.onbeforeunload;
		window.onbeforeunload = function() {
			abort();
			return oldbeforeunload ? oldbeforeunload() : undefined;
		}
	})(jQuery);




</script>

		<style>
/*			.view-as.group_all i{display: none;}
			.group_all .view-as.group_all i{ display:inline;}
*/
			.group_ .widget,.group_ .page-menu ul li{display:none;}

		</style>

		<script>

			var group_composites =[];


		// window.onload = function(){
			$('.page-menu ul li').each(function(my, item){
					var groups =($(item).data('groups')+"").split(',')
					for(var i in groups){
						$(item).addClass('group_'+groups[i]);
					}
				
			})




			$('.view-as').on('click', function(e){
				e.stopPropagation();
				if($(e.currentTarget).data('group') == 'reset'){

					$('body').attr('class',bodyClasses);

					for(var i in groups){
						$('body').toggleClass('group_'+groups[i]);

					}
					
				
				}else if($(e.currentTarget).data('group') == 'all'){
					$('body').attr('class',bodyClasses);
					
					for(var i in group_composites){
						$('.view-as.group_'+group_composites[i].id).click();
					}

				}else if($(e.currentTarget).data('group') == 'clear'){
					$('body').attr('class',bodyClasses);
					
				}else{
					//$('body').removeClass('group_all');

					$('body').toggleClass('group_'+$(e.currentTarget).data('group'));
					// if(window.getComputedStyle($(".page-menu .page_"+page_id)[0]).display == 'none'){
					// 	// modal({title:'HIDDEN',content:'This Page is not visible to members of this group'})

					// 	$('body').attr('class','app');
					// }
				}
			})
			groups = [];
			bodyClasses = $('body').attr('class');
			for(var i in groups){
				$('.view-as.group_'+groups[i]).click();
			}
			var visit = {
				width:window.innerWidth
			}
			if(resource_type !== null){
				visit.resource_type = resource_type;
				if(resource_id !== null){
					visit.resource_id = resource_id;
				}			
			}
			function inspect(row,element){
				formatter = new JSONFormatter(cb.collections[0].getItems()[0].bae.data,1, {hoverPreviewEnabled: true})
				mymodal = modal({title: "Widget Summary", content: formatter.render() });//templates.widgets_summary.render(cb.collections[row||0].getItems()[element||0].bae.data)});
			}
		</script>

		<!-- <script src="/assets/js/resources/creators.js"></script> -->

    <!-- End core_engine -->
