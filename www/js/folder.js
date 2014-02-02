$(function(){

    var rename = function(e){
        e.preventDefault();

        var $name = $(this);
        var $input = $('<input>');
        var $folder = $name.parent();
        $folder.data('href', $folder.attr('href'));
        $folder.removeAttr('href');
        $name.hide();
        $name.after($input);
        $input.val($name.text());
        $input.focus();
        $input.select();

        var done = function(){
            var newName = $input.val();

            $input.remove();
            $name.show();
            $folder.attr('href', $folder.data('href'));

            if(newName != $name.text() && newName != ""){
                $name.html(newName);

                $.get($name.data('rename'), {name:newName});
            }
        };

        $input.blur(function(){
            done();
        });

        $input.keypress(function(e){
            if(e.which == 13) {
                e.preventDefault();
                done();
            }
        });
    };

    $('.folder .name').click(rename);

    $('[data-new-folder]').click(function(){
        var $this = $(this);

        $.get($this.data('new-folder'), function(data){
            var $container = $('#'+$this.data('target'));
            var template = $container.find('.template').html();

            template = template.replace('__link__', data.link);
            template = template.replace('__rename__', data.rename);
            template = template.replace('__name__', data.name);
            template = template.replace(/^\s+|\s+$/g, '');

            var $folder = $(template);
            var $name = $folder.find('.name');

            $container.append($folder);
            $name.click(rename);
            $name.click();
        });
    });
});