Cobler.types.uApp = function(container){
	function get() {
		item.widgetType = 'uApp';
		return item;
	}
	var item = {
		guid: generateUUID()}
	var fields = {
		Title: {},
		'App ID': {type: 'select', choices: '/api/groups/'+group_id+'/appinstances'},
    'User Options':{name:'user_edit',type:'checkbox'},
		// 'Template': {}
	}
	return {
    container:container,
		fields: fields,
		render: function() {
      var temp = get();
      temp.uapp_admin = group_admin;
			return templates['widgets_microapp'].render(temp, templates);
		},
		edit: berryEditor.call(this, container),
		toJSON: get,
		get: get,
		set: function (newItem) {
			$.extend(item, newItem);
		},
		initialize: function(el){
    if(typeof this.get().app_id == 'undefined'){return false;};
      this.fields['App ID'].enabled = false;
      if(this.container.owner.options.disabled && this.get().enable_min){
          var collapsed = (Lockr.get(this.get().guid) || {collapsed:this.get().collapsed}).collapsed;
          this.set({collapsed:collapsed});
          $(el).find('.widget').toggleClass('cob-collapsed',collapsed)
      }

            data = this.get().init_data;
            var opts = {
              template: this.get().template || 'dashboard',
              $el: $(el).find('.collapsible'),
              crud: function(name, data, callback, verb){
                if (verb === 'GET') {
                  var content_type = 'application/x-www-form-urlencoded; charset=UTF-8'
                  var data_to_send = data;
                } else {
                  var content_type = 'application/json'
                  var data_to_send = JSON.stringify(data);
                }
                send_data = {request:data}
                // I'm sure there's a way better to get the app resources, but whatever
                app_resources = apps[0].app.code.resources;
                url = '/api'+gform.renderString(app_resources[name],send_data)
                $.ajax({
                url: url,
                type: verb,
                data:data_to_send,
                contentType: content_type,
                error: function (data) {
                  toastr.error(data.statusText, 'ERROR')
                }.bind(this),
                  success: callback.bind(this)
                });
              }
            }
            opts.data = data;
            opts.config = (_.find(apps, {id: this.get().app_id}) || _.find(Berry.collection.get('/api/groups/'+group_id+'/appinstances'), {id: this.get().app_id})).app.code || {};
            // opts.config = _.find(Berry.collection.get('/api/appinstances'), {id: parseInt(this.get().app_id,10)}).app.code;
            opts.config.app_instance_id = this.get().app_id;
            opts.config.title = this.get().title;
            $('style[name="'+opts.config.app_instance_id+'"]').remove();
            if(opts.config.css.length){
              $('body').append('<style name="'+opts.config.app_instance_id+'">'+opts.config.css+'</style>');
            }
            opts.onLoad = function(){
              this.bae.app.on('refetch', function(data){
                var options;
                if(typeof this.bae.data.user.id == 'undefined') {
                  options =  (Lockr.get('/api/apps/instances/'+this.get().app_id+'/user_options')|| {options:{}});
                }

                $.ajax({
                  type: 'POST',
                  url:'/api/fetch/'+this.get().app_id,
                  data:JSON.stringify(options),
                  contentType: "application/json",
                  success:function(data){
                      if(typeof data.user.id == 'undefined') {
                        var url = '/api/apps/instances/'+this.get().app_id+'/user_options';
                        data.user.options = (Lockr.get(url)|| {options:{}}).options;
                      }
                    this.bae.app.update(data);
                    // toastr.success('', 'Data refetched Successfully');
                  }.bind(this),
                  error:function(data){
                      toastr.error(data.statusText, 'An error occured updating App')
                  }
                })
              }.bind(this));
            }.bind(this)
            this.bae = grapheneAppEngine(opts);
            


		}
	}
}