{$location = '/account'}
{if !empty($cardId)}
    {$location = "/account/docs#attached-docs"}
{/if}

{capture name='page_scripts'}

<script>
    $(function(){
        if ($('#redirect').length > 0)
        {
            var _timer = 5;
            setInterval(function(){
                if (_timer > 0)
                {
                    $('#redirect').html('Вы будете перенаправлены в <a href="account">личный кабинет</a> через '+_timer+' сек.');
                }
                else
                {
                    if (!window.is_developer)
                        location.href = "{$location}";
                }
                _timer--;
                console.info(_timer)
            }, 1000);
        }
    });
</script>

{/capture}

{capture name='page_styles'}
    
{/capture}

<main class="main">
  <div class="section ">
    <div class="container">
      <div class="section_row row">
        <div class="col-12">

          {if $error}
            <div class="callback_error">{$error}</div>
          {/if}
          
          {if $success}
            <div class="callback_success">{$success}</div>
          {/if}
          
          {if $reason_code_description}
          <div class="reason_code_description">{$reason_code_description}</div>
          {/if}
          
          <div id="redirect" class="callback_redirect"></div>
          
        </div>
      </div>

    </div>
  </div>
</main>
