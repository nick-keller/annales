$(function(){
    var fileIconClass = '.i-file';

    var rename = function(){
        var $name = $(this);
        var $input = $('<input>');
        $name.hide();
        $name.after($input);
        $input.val($name.text());
        $input.focus();
        $input.select();

        var done = function(){
            var newName = $input.val();
            if(newName.indexOf('.pdf', newName.length - 4) === -1)
                newName += '.pdf';

            $input.remove();
            $name.show();

            if(newName != $name.text() && newName != ""){
                $name.html(newName);
                $name.attr('title', newName);
                $name.prev().attr('title', newName);

                $.get($name.data('rename'), {name:newName});
            }
        };

        $input.blur(function(){
            done();
        });

        $input.keypress(function(e){
            if(e.which == 13) done();
        });
    };

    $('.file .name').click(rename);

    var id = '#' + $('#upload-manager').data('file-id');

    $(id).change(function(){
        var $this = $(this);
        var $manager = $('#upload-manager');

        $.each($this[0].files, function(i, file){
            var html = $manager.find('.template').html();
            var size = file.size > 1000000 ? Math.round(file.size / 100000)/10 + 'Mo':Math.round(file.size / 1000) + 'ko';

            html = html.replace(new RegExp('__name__', 'g'), file.name);
            html = html.replace('__size__', size);
            html = html.replace('__id__', "file" + i);
            html = html.replace(/^\s+|\s+$/g, '');

            var $container = $(html);

            $manager.append($container);
        });

        function upload(i, files){
            var file = files[i];
            var size = file.size > 1000000 ? Math.round(file.size / 100000)/10 + 'Mo':Math.round(file.size / 1000) + 'ko';
            var $file = $('#file' + i);
            var $loadingBar = $file.find('.loading-bar');
            var $statusBar = $file.find('.status');
            // Time when last sample started
            var mesuredAt = new Date().getTime();
            // How much was sent when sample started
            var loadedWhenMesured = 0;
            // Upload speed in s/o
            var speed = 0;

            // Make sure we have a pdf
            if(file.type != 'application/pdf' && file.type != 'application/x-pdf'){
                $loadingBar.remove();
                $file.find(fileIconClass).css('color', '#ca6060');
                $file.removeAttr('id');
                $statusBar.html("Ceci n'est pas un pdf");

                if(++i < files.length)
                    upload(i, files);

                return;
            }

            // Make sure it's under 64 Mo
            if(file.size > 64000000){
                $loadingBar.remove();
                $file.find(fileIconClass).css('color', '#ca6060');
                $file.removeAttr('id');
                $statusBar.html("Fichier trop gros (64Mo max)");

                if(++i < files.length)
                    upload(i, files);

                return;
            }

            // Make loading bar blink
            $loadingBar.addClass('loading');
            $statusBar.html('Transfère...');

            var xhr = new XMLHttpRequest();

            xhr.open('POST', window.location);

            xhr.upload.onprogress = function(e) {
                $loadingBar.css('bottom', e.loaded / e.total * 100 + '%');

                var elapsed = new Date().getTime() - mesuredAt;

                // Recompute transfer speed every second
                if(elapsed > 1000){
                    speed = (1-0.05) * speed + (0.05) * elapsed / (e.loaded - loadedWhenMesured);

                    mesuredAt = new Date().getTime();
                    loadedWhenMesured = e.loaded;
                }

                var remaining = Math.round((speed  * (e.total - e.loaded))/1000);

                var minutes = Math.floor(remaining/60);
                var secondes = remaining - 60 * minutes;

                if(speed > 0)
                    $statusBar.html((minutes ? minutes + 'm ':'') + secondes + 's restantes');

            };

            xhr.onload = function() {
                $loadingBar.remove();
                var response = xhr.status == 200 ? JSON.parse(xhr.response): false;

                // An error occured
                if(xhr.status != 200 || !response.success){
                    $file.find(fileIconClass).css('color', '#ca6060');
                    $file.removeAttr('id');
                    $statusBar.html("Une erreur est survenue");
                }
                // Everything is good =)
                else{
                    $statusBar.html("Réussi");
                    $.get(response.template, function(data){
                        $file.before(data);
                        $file.prev().find('.name').click(rename);
                        $file.remove();
                    });
                }

                if(++i < files.length)
                    upload(i, files);
            };

            var form = new FormData();
            form.append($manager.data('name-name'), file.name);
            form.append($manager.data('size-name'), size);
            form.append($manager.data('file-name'), file);
            form.append($manager.data('token-name'), $manager.data('token-value'));

            xhr.send(form);
        }

        upload(0, $this[0].files);
    });


    $('[data-remove-files]').click(function(){
        var $this = $(this);
        var $files = $('.file[data-remove-file]');
        $this.toggleClass('active');

        if($this.hasClass('active')){
            $files.addClass('remove');
            $files.click(function(e){
                e.preventDefault();
                $.get($(this).data('remove-file'));
                $(this).animate({opacity:0}, 500, function(){
                    $(this).remove();
                })
            });
        }else{
            $files.removeClass('remove');
            $files.unbind("click");
        }
    });
});