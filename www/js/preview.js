$(function(){
    var $previewPanel = $('#preview-panel');
    // An array of all documents id in order
    var documentsOrder = new Array();
    var $loadingMessage = $previewPanel.find('.loading');

    $('[data-preview]').click(function(){
        loadDocument($(this).data('document-id'));
    }).each(function(){
        documentsOrder.push($(this).data('document-id'));
    });

    $previewPanel.find('.close-panel').click(function(){
        $previewPanel.hide();
    })

    // Opens a document in full screen based on its id
    function loadDocument(id){
        $previewPanel.show();
        $loadingMessage.show();
        $previewPanel.find('.document').hide();

        var $document = $previewPanel.find('[data-document-id='+id+']').first();

        // If the document was not found, we need to load it
        if($document.length == 0){
            var url = $('[data-document-id='+id+']').data('preview');
            $.get(url, function(data){
                $document = $(data);

                // Activate prev and next buttons
                $document.find('.prev-doc').click(function(){
                    loadNextDoc(id, -1);
                });
                $document.find('.next-doc').click(function(){
                    loadNextDoc(id, 1);
                });

                // Get first PDF url and activate every PDF buttons
                var firstPDF = $document.find('[data-pdf]').click(function(){
                    // If PDF is allready selected, nothing to do here
                    if($(this).parent().hasClass('active')) return;
                    showPDF($document, $(this).data('pdf'));
                }).data('pdf');

                $previewPanel.append($document);
                showPDF($document, firstPDF);
                loadDocument(id);
            });
            return;
        }
        $loadingMessage.hide();
        $document.show();
    }

    // If dir == 1 loads next document, if dir == -1 load previous document
    function loadNextDoc(id, dir){
        $.each(documentsOrder, function(i, doc){
            if(doc == id){
                i += dir;
                i %= documentsOrder.length;
                if(i<0) i += documentsOrder.length;
                loadDocument(documentsOrder[i]);
            }
        });
    }

    // Show a specific PDF of a document
    function showPDF($document, pdf){
        var $pdf = $('<embed></embed>');
        $pdf.attr('src', pdf);
        $document.find('.pdf').html($pdf);

        var $row = $document.find("[data-pdf='"+pdf+"']").parent();
        $row.parent().find('tr').removeClass('active');
        $row.addClass('active');
    }
});