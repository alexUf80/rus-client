;function AccountDocsApp()
{
    var app = this;
    
    var _init = function(){
        
        $(document).on('change', '#add_file', function(){
            app.upload(this);
        });
        
        
    }
        
    app.upload = function(input){
        
        var $this = $(input);
        
        var $fileblock = $this.closest('.form_file_item');
        var _type = $this.attr('data-type') || 'document';
        
        var form_data = new FormData();
                    
        form_data.append('file', input.files[0])
        form_data.append('type', _type);
        form_data.append('action', 'add');        
        form_data.append('notreplace', '1');        

        $.ajax({
            url: 'ajax/upload.php',
            data: form_data,
            type: 'POST',
            dataType: 'json',
            processData : false,
            contentType : false, 
            beforeLoad: function(){
                $fileblock.addClass('loading');
            },
            success: function(resp){
                if (!!resp.error)
                {
                    var error_text = '';
                    if (resp.error == 'max_file_size')
                        error_text = 'Превышен максимально допустимый размер файла.';
                    else if (resp.error == 'error_uploading')
                        error_text = 'Файл не удалось загрузить, попробуйте еще.';
                    else
                        error_text = resp.error;
                        
                    $fileblock.append('<div class="error_text">'+error_text+'</div>');
                }
                else
                {
                    $fileblock.find('.error_text').remove();
                    $fileblock.find('.js-need-new-card-photo').remove();
                    $fileblock.find('.js-success-upload').show();
                    var $row = '<li class="docs_list_item">';

                    var document_title = 'Документ';
                    if (_type === 'other_card') {
                        document_title = 'Дополнительная карта';
                    }

                    $row += '<a href="'+resp.filename+'" class="docs_list_link" data-fancybox>' + document_title + '</a>';
                    $row += '</li>';
                    
                    $('.js-docs-list').append($row);
                    
                }
                
            }
        });                
    };
    
    ;(function(){
        _init();
    })();
};

$(function(){
        new AccountDocsApp();
});