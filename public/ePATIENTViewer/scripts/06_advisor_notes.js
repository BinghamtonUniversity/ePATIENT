notes_init = function({team_id, team_name}={}) {
    this.data.localStorageNotesVar = 'epatient_notes_read_'
    this.data.notes = [];
    this.data.team_name = team_name
    this.data.newnotes = 0;
    
    $('#notes-modal').on('show.bs.modal', function (event) {
        fetch_notes.call(this,[],function() {
            if (typeof this.data.notes[0] !== 'undefined') {
                var maxId = this.data.notes[0].id;
            } else {
                maxId = null;
            }
            localStorage.setItem(this.data.localStorageNotesVar+this.data.team_id,maxId);
        });
    }.bind(this))
    
    $('#notes-modal').on('hide.bs.modal', function (event) {
        this.data.newnotes = 0;
        this.app.update();
    }.bind(this))
    
    // fetch_notes.call(this);
    // setInterval(fetch_notes.bind(this), 60000);
}
var fetch_notes = function(notes,callback) {
    notes = this.data.notes.concat(notes)
    var maxId = localStorage.getItem(this.data.localStorageNotesVar+this.data.team_id);
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
}
