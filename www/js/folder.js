$(function(){
    /*
     * Rename folder ====================================================
     */
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

    /*
     * New folder ===================================================
     */
    $('[data-new-folder]').click(function(){
        var $this = $(this);
        var $container = $('#'+$this.data('target'));
        var $loading = $('<i class="fa fa-spin fa-spinner fa-3x" style="margin-top: 30px"></i>');
        $container.append($loading);

        $.get($this.data('new-folder'), function(data){
            var template = $container.find('.template').html();
            $loading.remove();

            template = template.replace('__link__', data.link);
            template = template.replace('__rename__', data.rename);
            template = template.replace('__name__', data.name);
            template = template.replace('__id__', data.id);
            template = template.replace(/^\s+|\s+$/g, '');

            var $folder = $(template);
            var $name = $folder.find('.name');

            $container.append($folder);
            $name.click(rename);
            $name.click();
        });
    });

    /*
     * Add to folder ================================================
     */
    function activateAddToFolderLink($link){
        $link.click(function(){
            var $this = $(this);
            $this.addClass('active');
            $this.unbind("click");

            $.get($this.data('add-doc-to-folder'));
        });
    }

    $('[data-add-to-folder]').click(function(e){
        var $this = $(this);
        var $menu;
        var position = {
            of: $this,
            my: "left top",
            at: "left bottom+14",
            collision: "flip fit"
        };
        var arrowPosition = {
            of: $this,
            my: "center top",
            at: "center bottom+7"
        };

        e.stopPropagation();

        if($this.next().is('.add-to-folder')){
            $menu = $this.next();
            $menu.toggle();
            $('.add-to-folder').not($menu).hide();
            $menu.position(position);
        }else{
            $menu = $('<div class="add-to-folder"><div class="arrow"></div><i class="fa fa-spin fa-spinner icon-dark"></i></div>');
            $this.after($menu);
            $('.add-to-folder').not($menu).hide();
            $menu.position(position);
            $menu.find('.arrow').position(arrowPosition);
            $menu.click(function(e){
                e.stopPropagation();
            });

            $.get($this.data('add-to-folder'), function(data){
                $menu.html(data);
                $menu.position(position);
                $menu.find('.arrow').position(arrowPosition);
                activateAddToFolderLink($menu.find('[data-add-doc-to-folder]'));

                var $input = $menu.find('input');

                $input.keypress(function(e){
                    if(e.which == 13) {
                        e.preventDefault();
                        if($input.val() != ""){
                            $.get($input.data('new-folder'), {name:$input.val()}, function(data){
                                $('.add-to-folder').remove();
                                $this.click();
                            })
                            $input.val('');
                            $input.attr('placeholder', 'Création...');
                            $input.blur();
                        }
                    }
                });
            });
        }
    });

    $(document).click(function(){
        $('.add-to-folder').hide();
    });

    /*
     * Remove folder =========================================
     */
    $('[data-remove-folders]').click(function(){
        var $this = $(this);
        var $folders = $('.folder');
        $this.toggleClass('active');

        if($this.hasClass('active')){
            $folders.addClass('remove');
            $folders.click(function(e){
                e.preventDefault();
                $.get($(this).data('remove-folder'));
                $(this).animate({opacity:0}, 500, function(){
                    $(this).remove();
                })
            });
        }else{
            $folders.removeClass('remove');
            $folders.unbind("click");
        }
    });
});