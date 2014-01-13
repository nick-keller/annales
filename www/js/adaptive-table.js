$(function(){
    $(window).resize(function(){
        $('[data-adaptive-table]').each(function(i){
            var $table = $(this);
            $table.find('td,th').show();
            if($table.width() > $table.parent().width()){
                var columns = new Array();

                $table.find('tr').first().find('td,th').each(function(i){
                    var $col = $(this);
                    if($col.is('[data-priority]')){
                        columns.push({
                            rank: i+1,
                            priority: $col.data('priority')
                        });
                    }
                });

                columns.sort(function (a,b) {
                    if (a.priority < b.priority)
                        return 1;
                    if (a.priority > b.priority)
                        return -1;
                    return 0;
                });

                while($table.width() > $table.parent().width() && columns.length){
                    var col = columns.pop();
                    $table.find('td:nth-child(' + col.rank + '),th:nth-child(' + col.rank + ')').hide();
                }
            }
        });
    });
});