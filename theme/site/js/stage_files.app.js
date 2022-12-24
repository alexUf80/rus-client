;function StageFilesApp()
{
    var app = this;
    
    var _init = function(){
        
        $(document).on('change', '[type=file]', function(){
            app.upload(this);
        });
        
        
    }
    
    var _init_submit = function(){
        
        $('.js-stage-files-form').submit(function(e){
            var $form = $(this);
            $form.find('.form_file_item').each(function(){
                var $fileblock = $(this);
                if ($fileblock.find('.js-fileName').hasClass('uploaded'))
                    $fileblock.removeClass('-error')
                else
                    $fileblock.addClass('-error')
            });
            
            if ($(this).find('.form_file_item.-error').length > 0)
            {
                e.preventDefault();
                $('html').animate({ 
            	    scrollTop: $form.find('.form_file_item.-error').first().offset().top - 50 
                }, 500);
            }
        });
    };
    
    app.upload = function(input){
        
        var $this = $(input);
        
        var $fileblock = $this.closest('.form_file_item');
        var _type = $this.data('type');
        
        var form_data = new FormData();
                    
        form_data.append('file', input.files[0])
        form_data.append('type', _type);        
        form_data.append('action', 'add');        

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
                    
                    $fileblock.find('.js-fileName').addClass('uploaded').html('<img src="'+resp.filename+'" width="150" />');
                    
                }
                
            }
        });                
    };
    
    ;(function(){
        _init();
        _init_submit();
    })();
};

$(function(){
    if ($('.js-stage-files-form').length > 0)
        new StageFilesApp();
});