{$meta_title="Банковские карты" scope=parent}

{capture name='page_scripts'}

 <script src="theme/site/js/account_cards.app.js" type="text/javascript"></script>

{/capture}

{capture name='page_styles'}
    
{/capture}


<main class="main">
  <div class="section_lk_navbar">
    <div class="container">
      <nav class="navbar lk_menu">
        <ul class="nav lk_menu_nav -gil-m">
          <li class="nav-item">
            <a class="nav-link" href="account">Общая информация</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="account/history">История займов</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="account/cards">Банковские карты</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="account/data">Личные данные</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="account/docs">Документы</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="lk/logout">Выйти</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <div class="content_wrap">
    <div class="container">
      <h1>Личный кабинет</h1>
      
      <!-- карты -->
      <div class="person_info_box">
        <div class="person_info">
          <div class="person_info_title -fs-26 -gil-m">Привязанные карты</div>
          <div class="person_info_wrap -fs-18">
            
            {if $error}
            <div class="alert alert-danger">{$error}</div>
            {/if}
            
            {if $success}
            <div class="alert alert-success">{$success}</div>
            {/if}
            
            {*}
            <div class="account-cards mb-5">
                {foreach $cards as $card}
                <div class="account-card {if $card->base_card}base-card{/if}">
                    {if $card->base_card}
                        <span>Основная</span>
                    {else}
                        <a href="account/cards?base={$card->id}">Сделать основной</a>
                    {/if}
                    <a href="account/cards?remove={$card->id}" class="card-delete"></a>
                    <div class="card-pan">{$card->pan|escape}</div>
                    <div class="card-expdate">{$card->expdate|escape}</div>
                </div>
                {/foreach}
            </div>
            {*}
            
            <div class="card_list">
              <div class="card_list_row row">
                {foreach $cards as $card}
                <div class="col-md-6">
                  <div class="card_item">
                    <div class="card_item_num">
                        {$card->pan}
                        <div>
                        {if $card->base_card}
                            <span class="is_base_card">Основная</span>
                        {else}
                            <a class="make_base_card" href="account/cards?base={$card->id}">Сделать основной</a>
                        {/if}
                        </div>
                    </div>
                    <a href="account/cards?remove={$card->id}" class="card_item_close"></a>
                  </div>
                </div>
                {/foreach}
              </div>
              <div class="order_info_bottom more_btn">
                <a href="" class="btn btn-third js-attach-card">Привязать еще карту</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /карты -->
      
    </div>
  </div>
</main>
