{$meta_title="Прикрепленные документы" scope=parent}

{capture name='page_scripts'}
    <script src="theme/site/js/account_docs.app.js?v=1.02"></script>
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
                    <li class="nav-item">
                        <a class="nav-link" href="account/data">Личные данные</a>
                    </li>
                    <li class="nav-item active">
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
                    <div class="person_info_title -fs-26">Список документов</div>
                    <div class="person_info_wrap -fs-18">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="docs_list">
                                    {foreach $documents as $document}
                                            <li class="docs_list_item">
                                                <a href="{$config->root_url}/document/{$user->id}/{$document->id}"
                                                   class="docs_list_link" target="_blank">
                                                    {$document->name|escape}
                                                </a>
                                            </li>
                                    {/foreach}
                                    {if $recovers == 1}
                                        <li class="docs_list_item">
                                            <a href="theme/site/new/docs/Уведомление_о_привлечении_2_лица_для_взыскания_задолженности.pdf"
                                               class="docs_list_link" target="_blank">Уведомление о привлечении 2 лица для взыскания
                                                задолженности</a></li>
                                    {/if}
                                </ul>
                            </div>
                        </div>
                    </div>


                    <div id="attached-docs" class="person_info_title -fs-26">Список прикрепленных документов</div>
                    <div class="person_info_wrap -fs-18">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="docs_list js-docs-list">

                                    {if $files['passport1']}
                                        <li class="docs_list_item">
                                            <a href="{$config->user_files_dir}{$files['passport1']->name}"
                                               class="docs_list_link" data-fancybox>Скан паспорта</a>
                                        </li>
                                    {/if}
                                    {if $files['passport2']}
                                        <li class="docs_list_item">
                                            <a href="{$config->user_files_dir}{$files['passport2']->name}"
                                               class="docs_list_link" data-fancybox>Скан паспорта с регистрацией</a>
                                        </li>
                                    {/if}
                                    {if $files['face']}
                                        <li class="docs_list_item">
                                            <a href="{$config->user_files_dir}{$files['face']->name}"
                                               class="docs_list_link" data-fancybox>Фото с разворотом паспорта</a>
                                        </li>
                                    {/if}
                                    {if $files['card']}
                                        <li class="docs_list_item">
                                            <a href="{$config->user_files_dir}{$files['card']->name}"
                                               class="docs_list_link" data-fancybox>Фото карты</a>
                                        </li>
                                    {/if}

                                    {if $other_cards}
                                        {foreach $other_cards as $ocard}
                                            <li class="docs_list_item">
                                                <a href="{$config->user_files_dir}{$ocard->name}" class="docs_list_link"
                                                   data-fancybox>Дополнительная карта</a>
                                            </li>
                                        {/foreach}
                                    {/if}

                                    {if $other_files}
                                        {foreach $other_files as $ofile}
                                            <li class="docs_list_item">
                                                <a href="{$config->user_files_dir}{$ofile->name}" class="docs_list_link"
                                                   data-fancybox>Документ</a>
                                            </li>
                                        {/foreach}
                                    {/if}
                                    {*if !empty($receipts)}
                                        {foreach $receipts as $receipt}
                                            <li class="docs_list_item">
                                                <a href="{$receipt->receipt_url}" class="docs_list_link" target="_blank">Чек
                                                    об отмене услуги за {$receipt->created}</a>
                                            </li>
                                        {/foreach}
                                    {/if*}
                                    {*}
                                    <li class="docs_list_item">
                                      <a href="#" class="docs_list_link" data-fancybox>СНИЛС</a>
                                    </li>
                                    <li class="docs_list_item"><a href="#" class="docs_list_link" data-fancybox>Договор №1232-125215</a>
                                    </li>
                                    {*}
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="docs_list">
                                    {*foreach $documents as $document}
                                    <li class="docs_list_item">
                                      <a href="{$config->root_url}/document/{$user->id}/{$document->id}" class="docs_list_link" target="_blank">
                                          {$document->name|escape}
                                      </a>
                                    </li>
                                    {/foreach*}
                                </ul>
                            </div>
                        </div>
                    </div>
                    {if !empty($receipts)}
                        <div class="person_info_title -fs-26">Список чеков</div>
                        <div class="person_info_wrap -fs-18">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="docs_list js-docs-list">
                                        {foreach $receipts as $receipt}
                                            <li class="docs_list_item">
                                                <a href="{$receipt->receipt_url}"
                                                   class="docs_list_link" target="_blank">
                                                    Чек за {$receipt->created|date}
                                                </a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="person_info_bottom">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form_file_item">
                                <p></p>
                                {$type = ''}
                                {if !empty($otherCardAdded)}
                                    {$type = 'other_card'}
                                {/if}
                                <input type="file" name="file" id="add_file" class="input_file" data-type="{$type}"/>
                                <label for="add_file" class="btn btn-third js-labelFile">
                                    <span class="js-fileName">Добавить файл</span>
                                </label>
                                {if !empty($otherCardAdded)}
                                    <strong class="js-need-new-card-photo" style="color: red; margin-left: 15px;">Загрузите
                                        фото новой карты</strong>
                                {/if}
                                <strong class="js-success-upload"
                                        style="color: green; margin-left: 15px; display: none;">Успешно
                                    добавлено</strong>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /общая информация -->

        </div>
    </div>
</main>
