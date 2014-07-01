$(document).ready(function(){
    var UPDATE_META_TITLE = function(){
        var meta_title = $('input[name="Item[meta_title]"]').val();

        $('input[name="Item[meta_title]"]').keypress(function() {
            meta_title = $('input[name="Item[meta_title]"]').val();
        });

        $('input[name="Item[meta_title]"]').keyup(function() {
            meta_title = $('input[name="Item[meta_title]"]').val();
        });

        $('input[name="Item[title]"]').keypress(function() {
            if(meta_title == '')
            {
                $('input[name="Item[meta_title]"]').val(this.value);
            }
        });

        $('input[name="Item[title]"]').keyup(function() {
            if(meta_title == '')
            {
                $('input[name="Item[meta_title]"]').val(this.value);
            }
        });
    };

    UPDATE_META_TITLE();
});