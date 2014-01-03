$(function(){
    var id = '#' + $('#upload-manager').data('file-id');
    $(id).change(function(){
        var $this = $(this);
        var $manager = $('#upload-manager');

        $.each($this[0].files, function(i, file){
            var html = $manager.find('.template').html();
            var size = file.size > 1000000 ? Math.round(file.size / 100000)/10 + 'Mo':Math.round(file.size / 1000) + 'ko';

            html = html.replace('__name__', file.name);
            html = html.replace('__size__', size);
            html = html.replace('__id__', "file" + i);
            html = html.replace(/^\s+|\s+$/g, '');

            var $container = $(html);

            $manager.append($container);
        });

        function upload(i, files){
            var file = files[i];
            var $file = $('#file' + i);
            var $loadingBar = $file.find('.loading-bar');
            var $statusBar = $file.find('.status');

            // Make sure we have a pdf
            if(file.type != 'application/pdf' && file.type != 'application/x-pdf'){
                $loadingBar.remove();
                $file.find('.fa-file-text').css('color', '#ca6060');
                $statusBar.html("Ceci n'est pas un pdf");

                if(++i < files.length)
                    upload(i, files);

                return;
            }

            // Make sure it's under 64 Mo
            if(file.size > 64000000){
                $loadingBar.remove();
                $file.find('.fa-file-text').css('color', '#ca6060');
                $statusBar.html("Fichier trop gros (64Mo max)");

                if(++i < files.length)
                    upload(i, files);

                return;
            }

            // Make loading bar blink
            $loadingBar.addClass('loading');
            $statusBar.html('Transfère');

            var xhr = new XMLHttpRequest();

            xhr.open('POST', window.location);

            xhr.upload.onprogress = function(e) {
                $loadingBar.css('bottom', e.loaded / e.total * 100 + '%');
            };

            xhr.onload = function() {
                $loadingBar.remove();
                var response = JSON.parse(xhr.response);

                if(xhr.status != 200 || !response.success){
                    $file.find('.fa-file-text').css('color', '#ca6060');
                    $statusBar.html("Une erreur est survenue");
                }
                else{
                    $statusBar.html("Réussi");
                    $.get(response.template, function(data){
                        $file.before(data);
                        $file.remove();
                    });
                }

                if(++i < files.length)
                    upload(i, files);
            };

            var form = new FormData();
            form.append($manager.data('name-name'), file.name);
            form.append($manager.data('file-name'), file);
            form.append($manager.data('token-name'), $manager.data('token-value'));

            xhr.send(form);
        }

        upload(0, $this[0].files);
    });
});