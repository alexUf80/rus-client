{$meta_title="Смена пароля" scope=parent}

{capture name='page_scripts'}


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
          <li class="nav-item">
            <a class="nav-link" href="account/cards">Банковские карты</a>
          </li>
          <li class="nav-item active">
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
      
      <!-- общая информация -->
      <div class="person_info_box">
        <div class="person_info -gil-m">
          <div class="person_info_title -fs-26">
            {if $empty_password}
              <div class="mt-2 alert alert-success">
                В данном поле заполните удобный для вас пароль, в дальнейшем по нему будет осуществляться вход в личный кабинет
              </div>
            Добавить пароль
            {else}
            Изменить пароль
            {/if}
          </div>
          
          {if $error}
          <div class="mt-2 alert alert-danger">
            {$error}
          </div>
          {/if}
          
          {if $success}
          <div class="mt-2 alert alert-success">
            {$success}
          </div>
          {/if}
          
          <form class="pt-3" method="POST">
            <div class="row">
              <div class="col-md-6">
              <div class="form-group form-phone">
                <span class="phone_info -fs-14">
                    {if $empty_password}
                        Пароль для входа в кабинет
                    {else}
                        Новый пароль
                    {/if}
                </span>
                <input type="password" name="password" id="phone" class="form-control -fs-18 -gil-m js-login-phone js-mask-phone" value="" />
                <div class="error_text js-login-phone-error" style="display:none"></div>
              </div>
              <div class="form-group form-btn">
                <button type="submit" class="btn btn-secondary -fs-20 -fullwidth js-login-start">Сохранить</button>
              </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- /общая информация -->
      
    </div>
  </div>
</main>
