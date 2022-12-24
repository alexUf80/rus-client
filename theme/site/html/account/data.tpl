{$meta_title="Личные данные" scope=parent}

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
          <div class="person_info_title -fs-26">Персональные данные</div>
          <div class="person_info_wrap -fs-18">
            <div class="person_info_row">
              <div class="person_info_col">ФИО</div>
              <div class="person_info_col">{$user->lastname} {$user->firstname} {$user->patronymic}</div>
            </div>
            <div class="person_info_row">
              <div class="person_info_col">Дата рождения</div>
              <div class="person_info_col">{$user->birth}</div>
            </div>
            <div class="person_info_row">
              <div class="person_info_col">Пол</div>
              <div class="person_info_col">
                {if $user->gender == 'male'}Мужской
                {elseif $user->gender == 'female'}Женский
                {/if}
              </div>
            </div>
            <div class="person_info_row">
              <div class="person_info_col">Телефон</div>
              <div class="person_info_col">{$user->phone_mobile}</div>
            </div>
            <div class="person_info_row">
              <div class="person_info_col">Почта</div>
              <div class="person_info_col">{$user->email}</div>
            </div>
            <div class="person_info_row">
              <div class="person_info_col">Значение ПДН</div>
              <div class="person_info_col">{$user->pdn}</div>
            </div>
          </div>
          <div class="person_info_bottom">
            {*}
            <a href="#" class="btn btn-link -green">Изменить персональные данные</a>
            {*}
            <a href="account/password" class="btn btn-link -orange">Изменить пароль</a>

          </div>
        </div>
      </div>
      <!-- /общая информация -->
      
    </div>
  </div>
</main>
