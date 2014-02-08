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

    $('[data-add-to-folder]').click(function(e){
        var $this = $(this);
        var $menu;
        var position = {
            of: $this,
            my: "left top",
            at: "left bottom+7",
            collision: "flip fit"
        };

        e.stopPropagation();

        if($this.next().is('.add-to-folder')){
            $menu = $this.next();
            $menu.toggle();
            $('.add-to-folder').not($menu).hide();
            $menu.position(position);
        }else{
            $menu = $('<div class="add-to-folder"><i class="fa fa-spin fa-spinner icon-dark"></i></div>');
            $this.after($menu);
            $('.add-to-folder').not($menu).hide();
            $menu.position(position);

            $.get($this.data('add-to-folder'), function(data){
                $menu.html(data);
                $menu.position(position);
            });
        }
    });

    $(document).click(function(){
        $('.add-to-folder').hide();
    });
});