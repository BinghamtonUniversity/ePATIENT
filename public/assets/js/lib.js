toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-bottom-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

function generateUUID(){
    var d = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    return uuid;
};
function render(template, data){
  if(typeof templates[template] === 'undefined'){
    templates[template] =  Hogan.compile($('#'+template).html());
  }
return templates[template].render(data, templates);
}
function modal(options) {
  if(typeof options == 'string'){
    options = {content:options};
  }
  var hClass = ''
  switch(options.status){
    case 'error':
      hClass = 'bg-danger';
      break;
    case 'success':
      hClass = 'bg-success';
      break;
    case 'primary':
      hClass = 'bg-primary';
      break;
    case 'info':
      hClass = 'bg-info';
      break;
    case 'warning':
      hClass = 'bg-warning';
      break;
  }
  new gform({legend:options.title,modal:{header_class:hClass},fields:[{type:'output',name:'modal',label:false,format:{},value:gform.m(options.content,_.extend({}, this.partials, data))}],actions:[{type:'cancel',label:'<i class="fa fa-times"></i> Close',"modifiers": "btn btn-default pull-right"}]}).modal().on('cancel',function(e){
    e.form.dispatch('close');
    e.form.destroy();
  });
  
  // debugger;
  // $('#myModal').remove();
  // this.ref = $(render('modal', options));

  // options.legendTarget = this.ref.find('.modal-title');
  // options.actionTarget = this.ref.find('.modal-footer');

  // $(this.ref).appendTo('body');

  // if(options.content) {
  //   $('.modal-body').html(options.content);
  //   options.legendTarget.html(options.legend);
  // }else{
  //   options.autoDestroy = true;
  //   var myform = this.ref.find('.modal-body').berry(options).on('destroy', $.proxy(function(){
  //     this.ref.modal('hide');
  //   },this));

  //   this.ref.on('shown.bs.modal', $.proxy(function () {
  //     this.$el.find('.form-control:first').focus();
  //   },myform));
  // }
  // if(options.onshow){
  //   this.ref.on('shown.bs.modal', options.onshow);
  // }  
  // this.ref.modal();
  // return this;
};


function processFilter(options){
  options = options || {};
	var	currentTarget = options.currentTarget || this.currentTarget;
	var collection;
	if(this.selector){
		collection = $(this.selector).find('.filterable')
	}else{
		collection = $('.filterable');
	}
	collection.each(
	function(){
    if($.score($(this).text().replace(/\s+/g, " ").toLowerCase(), $(currentTarget).val().toLowerCase() ) > ($(currentTarget).data('score') || 0.40)){
      $(this).removeClass('nodisplay');
		}else{
			$(this).addClass('nodisplay');
		}
	});
}


filterTimer = null;
$('body').on('keyup','[name=filter]', function(event){

	this.currentTarget = event.currentTarget;
	this.selector = $(this).data('selector');
	if(!$(this).hasClass("delay")){
		processFilter.call(this);
	}else{
  	clearTimeout(filterTimer);
  	filterTimer=setTimeout($.proxy(processFilter, this), 300);
	}
});

templates.listing = Hogan.compile('<ol class="list-group">{{#widgets}}<li data-guid="{{guid}}" class="list-group-item"><div class="handle"></div>{{widgetType}} - {{title}}</li>{{/widgets}}</ol>')

updateActivity = function(item){
  var object = this.data.scenario;
  var path = item.form;
  var action =item.event;
  item.data = item.data||{};
  var value = item.data;
  item.data.user = value.user|| item.user

  if(typeof page_map[path.split('.')[0]]!== 'undefined'  && typeof page_map[path.split('.')[0]].root !== 'undefined'){
    var temp = path.split('.')
    temp[0] = page_map[path.split('.')[0]].root;
    path = temp.join('.')
  }
  var index  = null;
  _.reduce(path.split('.'),function(i,map,a,b,c){
    // var isNumber = !_.isNaN(parseInt(b[a+1]));

    // if(action == 'delete' &&  isNumber && b.length-2 == a){
    //   // debugger;
    //   // i[map].splice(parseInt(b[a+1]),1)
    //   // delete i[map][b[a+1]];
    //   // i[map] = _.compact(i[map]);
    // }
    if(typeof i[map] == "undefined"){
      i[map] = {};
    }
    if((b.length-1)!=a){
      return i[map];
    }else{
      switch (action){
        case "create":
          if(!_.isArray(i[map])){
            i[map] = [];
          }
          i[map].push(value);
          index = i[map].length-1;
          break;
        case "update":
          // if(!_.isArray(i) && !_.isNaN(parseInt(map))){
          //   i = [];
          // }
          i[map] = value;
          index = map;
          break;
        case "delete":
          if(_.isArray(i)){
            i.splice(parseInt(map),1);
          }else{
          delete i[map];
          i = _.compact(i);

          }

          // i = _.compact(i);
          // return i;
          // return i[map];

          break;
      }
    }
  },object)
  
  if( typeof gform.instances[item.form.split('.')[0]] !== 'undefined' && 
      (action == 'create' || action == 'update') && 
      (index+'' == this.data.hashParams.id && typeof this.data.hashParams.id !== "undefined")
  ){
    // debugger;
    gform.instances[item.form.split('.')[0]].set(item.data)
  }

  // if(_.isArray(temp)){
  //   temp = _.compact(temp); 
  // }
}





_.mixin({
  selectPath: function(object,path){
    return _.reduce(_.toPath(path),function(i,map){
      if(typeof i == 'object' && i !== null){
        return i[map];
      }else{
        return undefined;
      }
    },object)
  }
});
