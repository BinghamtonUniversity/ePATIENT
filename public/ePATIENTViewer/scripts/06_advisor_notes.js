this.data.notes = [];
this.data.newnotes = 0;
this.data.localStorageNotesVar = 'epatient_notes_read_'

notes_init = function() {
    $('#notes-modal').on('show.bs.modal', function (event) {
        if (typeof this.data.notes[0] !== 'undefined') {
            var maxId = this.data.notes[0].id;
        } else {
            maxId = null;
        }
        localStorage.setItem(this.data.localStorageNotesVar+this.data.team_id,maxId);
    }.bind(this))
    
    $('#notes-modal').on('hide.bs.modal', function (event) {
        this.data.newnotes = 0;
        _.forEach(this.data.notes, function (obj) { obj.isnew = false });
        this.app.update();
    }.bind(this))    
}

notes_add_notes = function(notes) {
    if (notes.length > 0) {
        notes = this.data.notes.concat(notes)
        var maxId = localStorage.getItem(this.data.localStorageNotesVar+this.data.team_id);
        notes = _.sortBy(notes, 'updated_at').reverse();
        this.data.newnotes = 0
        for(var i=0;i<notes.length;i++) {
            if (notes[i].id > maxId) {
                notes[i].isnew = true;
                this.data.newnotes+=1;
            }
        }
        this.data.notes = notes;
        this.app.update();
    }
}
