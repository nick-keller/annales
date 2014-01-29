$(function(){
    $('[data-ignore-exam]').click(function(){
        var $this = $(this);
        $.get($this.data('ignore-exam'));

        $this.parents('.event').animate({opacity:0, height:0}, 500, function(){
            $(this).remove();
        });
    });

    $('[data-unignore-exam]').click(function(e){
        e.preventDefault();
        var $this = $(this);
        $.get($this.data('unignore-exam'));

        $this.css('overflow', 'hidden').animate({opacity:0, width:0, padding:0}, 500, function(){
            $(this).remove();
        });
    });

    $('.full-size-event').click(function(){
        var $this = $(this);
        var $icon = $this.find('i');
        var $event = $this.parents('.event');

        $icon.toggleClass('i-resize-full').toggleClass('i-resize-small');
        $event.toggleClass('active');
        if($event.hasClass('active')){
            $event.animate({width:$event.parent().width()}, 500, function(){
                $event.css('width', '100%');
            })
        }
    })
});