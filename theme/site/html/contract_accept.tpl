{$meta_title="Подписание договора" scope=parent}

{capture name='page_scripts'}
    
    <script src="theme/site/js/contract_accept.app.js?v=1.14"></script>

{/capture}

{capture name='page_styles'}
    
{/capture}


<main class="main">
  <div class="section_lk_navbar">
    
  </div>
  <div class="content_wrap">
    <div class="container">
      <h1>Подписание договора</h1>
      
      <!-- общая информация -->
      <div class="person_info_box">
        <div class="person_info -gil-m">
          <form  id="accept_credit_form" data-phone="{$user->phone_mobile}">  
              <input type="hidden" name="contract_id" value="{$contract->id}" />
              <input type="hidden" name="phone" value="{$user->phone_mobile}" />
              <input type="hidden" name="accept_code" value="" />
              
              <div class="person_info_title -fs-26">Договор #{$contract->id}</div>
              
              <div class="pt-4">
                  <a href="javascript:void(0);" class="btn btn-primary js-accept-contract" >Подписать договор</a>
              </div>  
          </form>
        </div>
      </div>
      <!-- /общая информация -->
      
    </div>
  </div>
</main>
