$(function(){
    $('[data-ignore-exam]').click(function(){
        var $this = $(this);
        $.get($this.data('ignore-exam'));

        $this.parents('tr').animate({opacity:0, height:0}, 500, function(){
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
});