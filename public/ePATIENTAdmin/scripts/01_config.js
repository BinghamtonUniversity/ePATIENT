this.data.apps_pages = [
    {
        "name":"Users",
        "icon":"user",
        "slug":"table",
        "resource":"users",
        "permission":"manage_users"
    },
    {
        "name":"Teams",        
        "icon":"users",
        "slug":"table",
        "resource":"teams",
        "permission":"manage_teams"
    },
    {
        "name":"Scenarios",        
        "icon":"notes-medical",
        "slug":"table",
        "resource":"scenarios",
        "permission":"manage_scenarios"
    },
    {
        "name":"Library",
        "icon":"book",
        "id":1,
        "pages":[
            {name:'Products',icon:"pills",slug:'table',"resource":"products","permission":"manage_products"},
            {name:'Prescribers',icon:"user-md",slug:'table',"resource":"prescribers","permission":"manage_prescribers"},
            {name:'Solutions',icon:"user-md",slug:'table',"resource":"solutions","permission":"manage_solutions"},
            {name:'Labs',icon:"flask",slug:'table',"resource":"labs","permission":"manage_labs"},
        ]
    }
];

/* Remove pages that the current user does not have permissions to see */
// _.remove(this.data.apps_pages, function(obj) {
//     return !this.data.user.permissions[obj.permission];
// }.bind(this));
    
this.data.tables = {
    teams: {
        click: function(model){
            window.location.hash = 'page=team&team=' + model.attributes.id;
        }
    }
}