$(function() {
    // Simple autocomplete
    $( "[data-autocomplete]" ).autocomplete({
        delay: 0,
        minLength: 1,
        autoFocus: true
    })
    .each(function() {
        $(this).autocomplete("option", "source", $(this).attr('data-autocomplete').split(','));
    });

    // Multiple autocomplete
    function getQuery(elem){
        var str, before, after, current, position;

        str = elem.val();
        position = elem.caret();

        before = str.substr(0, position);
        if(before.lastIndexOf(' ') != -1)
            before = before.substr(0, before.lastIndexOf(' ') + 1);
        else
            before = "";

        after = str.substr(position);
        if(after[0] == ' ')
            after = after.substr(1);

        current = str.substr(before.length, position - before.length);

        return {before : before, current : current, after : after};
    }

    $( "[data-autocomplete-multiple]" ).autocomplete({
        focus: function() {
            return false;
        },
        delay: 0,
        minLength: 1,
        autoFocus: true
    })
    .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
            event.preventDefault();
        }
    })
    .each(function() {
        var $this = $(this);
        var availableTags = $this.attr('data-autocomplete-multiple').split(',');

        $this.autocomplete("option", "source", function( request, response ) {
            response( $.ui.autocomplete.filter( availableTags, getQuery( $this ).current ) );
        })
        .on("autocompleteselect", function( event, ui ) {
            var data, str;

            data = getQuery( $this );
            str = data.before + ui.item.value + " " + data.after;

            $this.val(str);
            $this.caret(data.before.length + ui.item.value.length + 1);
            return false;
        });
    });
});