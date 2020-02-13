<!-- Begin core_engine -->
<script src='/assets/js/grapheneAppEngine.js'></script> 
<script src='/assets/js/vendor/moment.js'></script>
<script src='/assets/js/vendor/jquery.min.js'></script>
<script src="/assets/js/vendor/bootstrap.min.js"></script>
<script src='/assets/js/vendor/hogan.min.js'></script>
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
<!-- <script src="/assets/js/vendor/ace/ace.js" charset="utf-8"></script> -->
<!-- <script src="/assets/js/vendor/jquery.nivo.slider.min.js" charset="utf-8"></script> -->
<script src="/assets/js/vendor/sortable.js"></script>
<script src='/assets/js/cob/cob.js'></script>
<script src='/assets/js/cob/content.cob.js'></script>
<script src='/assets/js/cob/image.cob.js'></script>
<script src='/assets/js/cob/uapp.cob.js'></script>
<script src='/assets/js/cob/flow.cob.js'></script>
<script src='/assets/js/cob/links.cob.js'></script>
<script src='/assets/js/templates/widget.js'></script>
<script src='/assets/js/lib.js'></script> 
<script src='/assets/js/vendor/sugar.js'></script> 
<script src='/assets/js/utils.js'></script> 
<script src='/assets/js/chat.js'></script> 
<script src='/assets/js/advisor_notes.js'></script> 
<script src='/assets/js/collections.js'></script> 
<script src='/assets/js/vital_sections.js'></script> 
<script src='/assets/js/page_map.js'></script> 

<script src='https://drvic10k.github.io/bootstrap-sortable/Scripts/bootstrap-sortable.js'></script>	
<style>
    .group_ .widget,.group_ .page-menu ul li{display:none;}
</style>
<script>
	resource_type = "app";
    var resource_id = "ePATIENTViewer";
    var mobile_order = [];
    var apps = [{!! $app_definition !!}];
    var init_data = {!! $init_data !!};
	var config = {"sections":[[{
        "title":"View",
        "app_id":"ePATIENTViewer",
        "widgetType":"uApp",
        "container":true,
        "init_data":init_data
    }]]};
	_.findWhere = _.find;
	_.where = _.filter;
	
	group_id = 0;
	
	group_admin = false;
	if(group_admin){
		$('body').addClass('admin');
	}
		
    appMode = true;

	layouts = {
        value: '4',
        classes: 'bu-12-12',
        title: 'Full Page (12)',
        label: '<i title="Full" class="bu-12-12"></i>',
        template: '<div class="col-lg-12 cobler_container"></div>'
    };

	function load(status){
		$('body').toggleClass('editor', !status)
		if(typeof cb !== 'undefined'){
			cb.destroy();
			delete cb;
		}
		var template = 'widgets_container'
        var target = 'widget';
		var data = config.sections || [[]];
        $('#page_layout').html(layouts.template);
		cb = new Cobler({ disabled: status, targets: document.getElementsByClassName('cobler_container'),itemContainer: template,itemTarget:target, items:data})
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

    function inspect(row,element){
        formatter = new JSONFormatter(cb.collections[0].getItems()[0].bae.data,1, {hoverPreviewEnabled: true})
        mymodal = modal({title: "Widget Summary", content: formatter.render() });//templates.widgets_summary.render(cb.collections[row||0].getItems()[element||0].bae.data)});
    }
</script>
    <!-- End core_engine -->
