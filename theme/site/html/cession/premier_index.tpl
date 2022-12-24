<!DOCTYPE html>
<html>

<head>
    <base href="{$config->root_url}/" />

  <meta charset="utf-8">
  <title>{$meta_title|escape}</title>
  <meta name="description" content="{$meta_keywords|escape}">
  <link rel="shortcut icon" href="theme/site/i/favicon/favicon.ico" type="image/x-icon">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- style -->
  <link rel="stylesheet" href="theme/site/libs/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="theme/site/libs/range/ion.rangeSlider.min.css" />
  <link rel="stylesheet" href="theme/site/libs/fancybox/jquery.fancybox.min.css">
  <link rel="stylesheet" href="theme/site/libs/jquery/jquery-ui/jquery-ui.min.css" />
  <link rel="stylesheet" href="theme/site/libs/jquery/jquery-ui/jquery-ui.theme.min.css" />
  <link rel="stylesheet" href="theme/site/css/common.css?v=1.04" />
  <link rel="stylesheet" href="theme/site/css/custom.css?v=1.04" />
  
  {$smarty.capture.page_styles}
  
  <script>
    window.is_developer = {$is_developer}
    {if $is_developer}console.info('DEVELOPER MODE'){/if}
  </script>  
</head>
<body class="{if in_array($module, ['MainController'])}home{else}page{/if}">
  
  {*if $is_developer}
  <style>
    .developer-panel {
        background:#d22;
        color:#fff;
        padding:10px;
        width:100%;
    }
    .developer-panel:after {
        content:'';
        display:block;
        clear:both;
    }
    .developer-panel strong {
        float:left;
        font-size:20px;
    }
    .developer-panel span {
        float:right;
        font-size:20px;
    }
  </style>
  <div class="developer-panel">
    <strong>DEVELOPER MODE</strong>
    {if $user}
    <span>ID {$user->id}</span>
    {/if}
  </div>
  
  {/if*}  

  <div class="wrapper">
    <header class="header">
      <div class="container">
        <div class="header_row row">
          <div class="col-sm-6 col-md-4 col-lg-3 header-col-logo">
            <div class="header_logo">
              {*}
              <a href="/" class="logo"><img src="theme/site/i/logo.png" alt=""></a>
              {*}
              <a href="account" style=";padding-top:10px;display:block;font-size:42px;font-family: 'Gilroy-Bold';">Премьер</a>
            </div>
          </div>
          <div class="col-lg-4 header-col-nav">
            <div class="header_nav">
              <nav class="navbar header_menu">
                {*}
                <ul class="nav header_menu_nav -gil-m">
                  <li class="nav-item">
                    <a class="nav-link" href="page/how-get">Как получить</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/how-repay">Как погасить</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/about">О компании</a>
                  </li>
                </ul>
                {*}
              </nav>
            </div>
          </div>
          <div class="col-sm-6 col-md-8 col-lg-5 header-col-contact">
            <div class="header_right">
              <div class="header_right_row">
                <div class="header_btn">
                  {*if $user}
                  <a href="account" class="btn btn-primary -lk-btn">Личный кабинет</a>
                  {else}
                  <a href="user/login" class="btn btn-primary -lk-btn">Личный кабинет</a>
                  {/if*}
                </div>
                <div class="header_contacts">
                  {*}
                  <a href="tel:8800000000000" class="header_phone -gil-b -fs-24">8 800 0000 00 00</a>
                  <span class="info -fs-14">звонок бесплатный</span>
                  {*}  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    
    <header class="mobheader">
        <div class="mobheader-logo">
            <a href="/" class="logo">Премьер</a>
        </div>
        {*}
        <div class="mobheader-phone">
            <a href="tel:880000000000" class="">8 800 000 00 00</a>
            <span class="">звонок бесплатный</span>
        </div>
        <div class="mobheader-menu">
            <a class="mobheader-toggler" href="javascript:void(0);">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <div class="mobheader-menu-nav">
              <nav class="navbar header_menu">
                <ul class="nav header_menu_nav -gil-m">
                  <li class="nav-item">
                    <a class="nav-link" href="page/how-get">Как получить</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/how-repay">Как погасить</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/about">О компании</a>
                  </li>
                </ul>
              </nav>
            </div>
        </div>
        {*}
        <div class="mobheader-account">
          {if $user}
          <a href="account" class=""></a>
          {else}
          <a href="lk/login" class=""></a>
          {/if}            
        </div>
    </header>
    
    {$content}
    
    <footer class="footer" style="color:#111;">
      <div class="container">
        <div class="footer_row row">
          <div class="col-md-4 col-lg-2 footer-col-logo">
            {*}
            <div class="footer_logo">
              <a href="/" class="logo"><img src="theme/site/i/logo-white.svg" alt="logo"></a>
              <span class="logo_info -fs-15 -gil-l">Комфортные займы
                на вашу карту
                не выходя из дома</span>
            </div>
            {*}
          </div>
          <div class="col-lg-4 footer-col-nav">
            <div class="footer_nav">
              <nav class="navbar navbar-expand-lg footer_menu">
                <button class="navbar-toggler">
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                </button>
                {*}
                <ul class="nav footer_menu_nav -gil-m -fs-16">
                  <li class="nav-item">
                    <a class="nav-link" href="page/about">О компании</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/how-get">Как получить</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/requisites">Реквизиты</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/how-repay">Как погасить</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/documents">Документы</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/conditions">Условия</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="page/contacts">Контакты</a>
                  </li>
                </ul>
                {*}
              </nav>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-3 footer-col-btn">
            <div class="footer_btn">
              {*}
              <a href="#" class="btn btn_link">Консультация</a>
              <a href="#" class="btn btn_link">Договор оферты</a>
              {*}  
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-3 footer-col-contacts">
            <div class="footer_contacts -fs-14">
              {*}
              <a href="tel:880000000000" class="footer_phone -fs-24 -gil-b">8 800 0000 00 00</a>
              <span class="footer_text -gil-m">звонок бесплатный</span>
              <a href="mailto:info@nalichnoe.com" class="footer_email -gil-b">info@nalichnoe.com</a>
              <div class="work_time">
                <div class="work_time_title -gil-b">Время работы:</div>
                <div class="work_time_text">понедельник - пятница <br>с 9:00 до 21:00 </div>
              </div>
              {*}
            </div>
          </div>
        </div>
        <div class="footer_row">
          <div class="copyright -fs-15">Премьер. Все права защищены</div>
        </div>
      </div>
    
      <div class="hide">
        <div class="info-modal" id="sms_code_modal"></div>
        <div class="info-modal" id="error_modal">
            <span class="error-icon"></span>
            <span class="error-message js-error-message"></span>
        </div>
      </div>
        Error 440 - Login time-out. Пожалуйста обратитесь к разработчикам
      
    </footer>
  </div>
  <script src="theme/site/libs/jquery/jquery-3.4.1.min.js"></script>
  <script src="theme/site/libs/bootstrap/bootstrap.min.js"></script>
  <script src="theme/site/libs/range/ion.rangeSlider.min.js"></script>
  <script src="theme/site/libs/jquery/jquery.maskedinput.min.js"></script>
  <script src="theme/site/libs/fancybox/jquery.fancybox.min.js"></script>
  <script src="theme/site/libs/jquery/jquery-ui/jquery-ui.min.js"></script>
  <script src="theme/site/js/common.js"></script>

  <script src="theme/site/js/sms.app.js"></script>
  <script src="theme/site/js/attach_card.app.js"></script>
  
  {$smarty.capture.page_scripts}
  
  {if $message_error}
  <script>
    $('.js-error-message').html('{$message_error}');
    $.fancybox.open({
        src: '#error_modal'
    })
  </script>
  {/if}
  
  {if $user}
  <script>
    function save_local_time()
    {
        var date = new Date();
        var local_time = parseInt(date.getTime() / 1000);

        $.ajax({
            url: '/ajax/local_time.php',
            data: {
                local: local_time
            }
        });        
    }
    save_local_time();
    setInterval(function(){
        save_local_time();
    }, 30000);
    
  </script>
  {/if}
  
</body>
</html>
