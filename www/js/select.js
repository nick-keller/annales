$(function(){
    $('.select').each(function(){
        var $select = $(this);
        var $active = $select.find('.option.active');
        var $val =  $select.find('input');
        var $display = $select.find('.selected-value');

        if($active.length){
            $val.val($active.data('value'));
            $display.html($active.html());
        }else{
            var $first = $select.find('.option').first();
            $first.addClass('active');
            $val.val($first.data('value'));
            $display.html($first.html());
        }
    });

    $('.select').click(function(e){
        var $this = $(this);
        if(!$this.hasClass('active')){
            e.stopPropagation();

            $('.select').removeClass('active');
            $this.addClass('active');
        }
    });

    $('html').click(function(){
        $('.select').removeClass('active');
    })

    $('.select .option').click(function(){
        var $this = $(this);
        var $select = $this.parent().parent();
        var $val =  $select.find('input');
        var $display = $select.find('.selected-value');
        var $options = $select.find('.option');

        $options.removeClass('active');
        $this.addClass('active');
        $val.val($this.data('value'));
        $display.html($this.html());
    });
});