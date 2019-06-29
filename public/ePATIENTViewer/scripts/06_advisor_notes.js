notes_init = function({team_id, team_name}={}) {
    var localStorageVar = 'epatient_notes_read_'
    this.data.notes = {};
    this.data.team_name = team_name
    this.data.newnotes = 0;
    
    var fetch_notes = function(callback) {
        this.app.get('notes_list',{team_id:team_id},function(notes){
            var maxId = localStorage.getItem(localStorageVar+team_id);
            notes = _.sortBy(notes, 'updated_at').reverse();
            this.data.newnotes = false
            for(var i=0;i<notes.length;i++) {
                if (notes[i].id > maxId) {
                    notes[i].isnew = true;
                    this.data.newnotes+=1;
                }
            }
            this.data.notes = notes;
            this.app.update();
            if (typeof callback !== 'undefined') {
                callback.call(this);
            }
        })
    }
    
    $('#notes-modal').on('show.bs.modal', function (event) {
        fetch_notes.call(this,function() {
            var maxId = this.data.notes[0].id;
            localStorage.setItem(localStorageVar+team_id,maxId);
        });
    }.bind(this))
    
    $('#notes-modal').on('hide.bs.modal', function (event) {
        this.data.newnotes = 0;
        this.app.update();
    }.bind(this))
    
    fetch_notes.call(this);
    setInterval(fetch_notes.bind(this), 60000);
}