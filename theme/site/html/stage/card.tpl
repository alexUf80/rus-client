{$meta_title='Привязка карты' scope=parent}

{capture name='page_scripts'}

  <script src="theme/site/js/stage_card.app.js"></script>

{/capture}

{capture name='page_styles'}
    
{/capture}

<main class="main">
  <div class="section section_form">
    <div class="container">
      <div class="section_form_row row">
        <div class="col-lg-5">
          <div class="main_form_info">
            <div class="form_info_title -fs-28">
              <span class="-black">Пройдите короткую регистрацию</span>
              <span class="-orange">чтобы получить займ</span>
            </div>
            <div class="form_info_subtitle">
              <p>Вы выбрали сумму: <span class="sum">{$user->first_loan_amount} рублей</span></p>
              <p>На срок: <span class="days">{$user->first_loan_period} {$user->first_loan_period|plural:'день':'дней':'дня'}</span></p>
            </div>
            <div class="form_info_progress">
              <div class="form_info_progress_text -step5 active"><span class="step -green">Анкета заполнена, осталось привязать карту на которую Вы получите деньги</span></div>
              <div class="form_info_progress_control -step5"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="main_form">
            <form action="" method="" class="regform">
              
              <div class="step_box step5">
                
                <div class="step_box_btn">
                  <a href="stage/card?step=prev" class="btn btn_back btn-link -green -gil-m">Назад</a>
                  <a href="{$card_link}" class="btn btn_next btn-secondary -fs-20 js-attach-card">Привязать карту</a>
                </div>

              </div>

            </form>
          </div>
        </div>
      </div>



    </div>
  </div>
</main>
