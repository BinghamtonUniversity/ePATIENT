function readHash(){
    this.app.update({hashParams:QueryStringToHash(document.location.hash.substr(1) || '')})
    if(typeof this.data.hashParams.resource == 'undefined'){
        this.data.hashParams.resource = 'users';
    } 
    if(typeof bt !== 'undefined'){bt.destroy();}
    if(this.data.hashParams.page == 'table'){
        this.app.get(this.data.hashParams.resource,{},function(data){
            this.data[this.data.hashParams.resource] = data;
            bt = build_table.call(this,this.data.hashParams.resource,this.data.tables[this.data.hashParams.resource]);
        })
    }
    if(this.data.hashParams.page == 'team'){
        if(typeof mymembers !== 'undefined'){mymembers.destroy();}
        if(typeof mynotes !== 'undefined'){mynotes.destroy();}
        if(typeof mymessages !== 'undefined'){mymessages.destroy();}
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            window[$(e.target).attr('href').replace('#','my')].fixStyle()
        })
        this.app.get('teams',{ id: this.data.hashParams.team, resource:'members' }, function(data){
            var options = {
                container:'#members',
                data:data,
                schema:JSON.parse(_.find(this.options.config.forms,{name:'members'}).content).fields
            };
            options.schema[0].options = this.data.users;
            this.data.members = data;
            options.default = {"resource":"members", team_id:this.data.hashParams.team}
            mymembers = build_table.call(this, "teams", options);

            this.app.get('teams',{ id: this.data.hashParams.team, resource:'notes' }, function(data){
                var options = {container:'#notes',
                    data:data,
                    schema:JSON.parse(_.find(this.options.config.forms,{name:'notes'}).content).fields
                };                        
                options.schema[0].options = _.map(this.data.members,'user');
                options.default = {"resource":"notes",id:this.data.hashParams.team}
                mynotes = build_table.call(this, "teams", options);
            })
            this.app.get('teams',{ id: this.data.hashParams.team, resource:'messages' }, function(data){
                var options = {container:'#messages',
                    data:data,
                    schema:JSON.parse(_.find(this.options.config.forms,{name:'messages'}).content).fields
                };            
                options.schema[0].options = this.data.users;//_.map(this.data.members,'user');
                options.default = {"resource":"messages",id:this.data.hashParams.team}
                options.edit = false;
                options.add = false;
                options.delete = function(model){
                    this.app.delete('messages',{},function(){});
                }.bind(this)
                options.delete = false;
                mymessages = build_table.call(this, "teams", options);
            })
        })

    }
}
// debugger;
Berry.collection.add('roles',data.roles)
this.callback = function(){
    readHash.call(this);
    window.onhashchange = readHash.bind(this);
}