this.data.apps_pages = [
    {
        "name":"Users",
        "icon":"user",
        "slug":"table",
        "resource":"users"
    },
    {
        "name":"Roles",        
        "icon":"id-badge",
        "slug":"table",
        "resource":"roles"
    },
    {
        "name":"Teams",        
        "icon":"users",
        "slug":"table",
        "resource":"teams"
    },
    {
        "name":"Scenarios",        
        "icon":"notes-medical",
        "slug":"table",
        "resource":"scenarios"
    },
    {
        "name":"Library",
        "icon":"book",
        "id":1,
        "pages":[
            {name:'Products',icon:"pills",slug:'table',"resource":"products"},
            {name:'Prescribers',icon:"user-md",slug:'table',"resource":"prescribers"},
            {name:'Solutions',icon:"user-md",slug:'table',"resource":"solutions"},
            {name:'Labs',icon:"flask",slug:'table',"resource":"labs"},
        ]
    }
];
    
this.data.tables = {
    teams: {
        click: function(model){
            window.location.hash = 'page=team&team=' + model.attributes.id;
        }
    }
}