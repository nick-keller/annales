$(function(){
    $('[data-action]').click(function(){
        var $this = $(this);
        $this.unbind('click');
        $.get($this.data('action'));
    });
});