{$meta_title='Загрузка документов' scope=parent}

{capture name='page_scripts'}

  <script src="theme/site/js/stage_files.app.js"></script>

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
            {if !$have_reject_files}
            <div class="form_info_subtitle">
              <p>Вы выбрали сумму: <span class="sum">{$user->first_loan_amount} рублей</span></p>
              <p>На срок: <span class="days">{$user->first_loan_period} {$user->first_loan_period|plural:'день':'дней':'дня'}</span></p>
            </div>
            {/if}
            <div class="form_info_progress">
              <div class="form_info_progress_text -step1">До получения займа осталось: <span
                  class="step -green">4 этапа</span></div>
              <div class="form_info_progress_text -step2">До получения займа осталось: <span class="step -green">3
                  этапа</span></div>
              <div class="form_info_progress_text -step3">До получения займа осталось: <span class="step -green">2
                  этапа</span></div>
              <div class="form_info_progress_text -step4">До получения займа осталось: <span class="step -green">1
                  этап</span></div>
              <div class="form_info_progress_text -step5 active"><span class="step -green">Последний этап</span></div>
              <div class="form_info_progress_control -step5"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="main_form">
            <form action="" method="POST" class="regform js-stage-files-form">
              
              <div class="step_box step5">
                <div class="form_group -fs-18">
                  <div class="form_group-title -gil-m">
                    {if $have_reject_files}
                    Замените документы:
                    {else}
                    Загрузите документы:
                    {/if}
                  </div>
                  
                  
                  {if !$have_reject_files || ($have_reject_files && $user_files['passport1']->status == 3)}
                  <div class="form_file_item {if $user_files['passport1']->status == 3}rejected{/if}">
                    <p>Первая страница паспорта</p>
                    <input type="file" name="file1" id="file1" data-type="passport1" class="input_file">
                    <label for="file1" class="btn js-labelFile">
                      <span class="js-fileName {if $user_files['passport1']}uploaded{/if}">
                        {if $user_files['passport1']}
                        <img src="{$config->user_files_dir}{$user_files['passport1']->name}" width="150" />
                        {else}
                        <img src="/theme/site/i/passport.png" width="150" />
                        {/if}
                      </span>
                        {if $user_files['passport1']->status == 3}
                            <div class="-gil-m -fs-16 -red text-center">Заменить файл</div>
                        {else}
                            <div class="-gil-m -fs-16 -green text-center">Добавить файл</div>
                        {/if}
                    </label>
                    {if $user_files['passport1']->status == 3}
                    <p class="text-danger">Файл не прошел проверку, загрузите новый</p>
                    {/if}
                  </div>
                  {/if}
                  
                  {if !$have_reject_files || ($have_reject_files && $user_files['passport2']->status == 3)}
                  <div class="form_file_item {if $user_files['passport2']->status == 3}rejected{/if}">
                    <p>Страница с пропиской</p>
                    <input type="file" name="file2" id="file2" data-type="passport2" class="input_file">
                    <label for="file2" class="btn js-labelFile">
                      <span class="js-fileName {if $user_files['passport2']}uploaded{/if}">
                        {if $user_files['passport2']}
                        <img src="{$config->user_files_dir}{$user_files['passport2']->name}" width="150" />
                        {else}
                        <img src="/theme/site/i/registration.png" width="150" />
                        {/if}
                      </span>
                        {if $user_files['passport2']->status == 3}
                            <div class="-gil-m -fs-16 -red text-center">Заменить файл</div>
                        {else}
                            <div class="-gil-m -fs-16 -green text-center">Добавить файл</div>
                        {/if}
                    </label>
                    {if $user_files['passport2']->status == 3}
                    <p class="text-danger">Файл не прошел проверку, загрузите новый</p>
                    {/if}
                  </div>
                  {/if}
                  
                  {if !$have_reject_files || ($have_reject_files && $user_files['face']->status == 3)}
                  <div class="form_file_item {if $user_files['face']->status == 3}rejected{/if}">
                    <p>Ваше фото с разворотом паспорта</p>
                    <input type="file" name="file3" id="file3" data-type="face" class="input_file">
                    <label for="file3" class=" js-labelFile">
                      <span class="js-fileName {if $user_files['face']}uploaded{/if}">
                        {if $user_files['face']}
                        <img src="{$config->user_files_dir}{$user_files['face']->name}" width="150" />
                        {else}
                        <img src="/theme/site/i/selfi.png" width="150" />
                        {/if}
                      </span>
                        {if $user_files['passport2']->status == 3}
                            <div class="-gil-m -fs-16 -red text-center">Заменить файл</div>
                        {else}
                            <div class="-gil-m -fs-16 -green text-center">Добавить файл</div>
                        {/if}
                    </label>
                    {if $user_files['face']->status == 3}
                    <p class="text-danger">Файл не прошел проверку, загрузите новый</p>
                    {/if}
                  </div>
                  {/if}
                  
                  {if !$have_reject_files || ($have_reject_files && $user_files['card']->status == 3)}
                  <div class="form_file_item {if $user_files['card']->status == 3}rejected{/if}">
                    <p>Фото личной банковской карты</p>
                    
                    <input type="file" name="file4" id="file4" data-type="card" class="input_file">
                    <label for="file4" class="btn js-labelFile">
                      <span class="js-fileName {if $user_files['card']}uploaded{/if}">
                        {if $user_files['card']}
                        <img src="{$config->user_files_dir}{$user_files['card']->name}" width="150" />
                        {else}
                        <img src="/theme/site/i/card.png" width="150" />
                        {/if}
                      </span>
                        {if $user_files['card']->status == 3}
                            <div class="-gil-m -fs-16 -red text-center">Заменить файл</div>
                        {else}
                            <div class="-gil-m -fs-16 -green text-center">Добавить файл</div>
                        {/if}
                    </label>
                    {if $user_files['card']->status == 3}
                    <p class="text-danger">Файл не прошел проверку, загрузите новый</p>
                    {/if}
                  </div>
                  {/if}
                  
                  {foreach $other_user_files as $other_user_file}
                  {if !$have_reject_files || ($have_reject_files && $other_user_file->status == 3)}
                  <div class="form_file_item {if $user_files['card']->status == 3}rejected{/if}">
                    <p>Документ</p>
                    
                    <input type="file" name="file_{$other_user_file@index}" id="file_{$other_user_file@index}" data-type="document" class="input_file">
                    <label for="file_{$other_user_file@index}" class="btn js-labelFile">
                      <span class="js-fileName {if $user_files['card']}uploaded{/if}">
                        <img src="{$config->user_files_dir}{$other_user_file->name}" width="150" />
                      </span>
                        {if $other_user_file->status == 3}
                            <div class="-gil-m -fs-16 -red text-center">Заменить файл</div>
                        {else}
                            <div class="-gil-m -fs-16 -green text-center">Добавить файл</div>
                        {/if}
                    </label>
                    {if $other_user_file->status == 3}
                    <p class="text-danger">Файл не прошел проверку, загрузите новый</p>
                    {/if}
                  </div>
                  {/if}
                  {/foreach}
                  
                </div>
                
                <div class="step_box_btn">
                  {if !$have_reject_files}  
                  <a href="stage/files?step=prev" class="btn btn_back btn-link -green -gil-m">Назад</a>
                  {/if}
                  <button type="submit" class="btn btn_next btn-secondary -fs-20">Получить займ</button>
                </div>

              </div>

            </form>
          </div>
        </div>
      </div>



    </div>
  </div>
</main>

