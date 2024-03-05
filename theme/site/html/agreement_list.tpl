<div id="agreement_list" style="display:none">
    <ul>
    </ul>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="pers" value="1" />
                <input type="checkbox"
                       class="custom-checkbox  js-need-check" {if !in_array($user->phone_mobile, ['79171018924', '79179400617'])}{/if}
                       id="pers" value="1" {if !$safe_mode}checked{/if}/>
                <label for="pers" class="check_box -gil-m">
                 <span>
                    Согласие на обработку
                    <a href="theme/site/new/docs/Согласие_на_обработку_персональных_данных.pdf" target="_blank"
                       style="color: RGB(189, 148, 87)">персональных данных</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="pers" value="1" />
                <input type="checkbox"
                       class="custom-checkbox  js-need-check" {if !in_array($user->phone_mobile, ['79171018924', '79179400617'])}{/if}
                       id="pers" value="1" {if !$safe_mode}checked{/if}/>
                <label for="pers" class="check_box -gil-m">
                 <span>
                    Согласие на получение 
                    <a href="theme/site/new/docs/Согласие_на_получение_информации_от_БКИ.pdf" target="_blank"
                       style="color: RGB(189, 148, 87)">информации от БКИ</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="soglasie_pep" value="1"/>
                <input type="checkbox" class="custom-checkbox js-need-check" id="soglasie_pep" value="1" {if !$safe_mode}checked{/if}/>
                <label for="soglasie_pep" class="check_box -gil-m">
                 <span>
                     Соглашение
                    <a style="color: RGB(189, 148, 87)" href="theme/site/new/docs/соглашение_об_использовании_прсотой_электронной_подписи.pdf" target="_blank"> АСП</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="pravila" value="1"/>
                <input type="checkbox" class="custom-checkbox js-need-check" id="pravila" value="1" {if !$safe_mode}checked{/if}/>
                <label for="pravila" class="check_box -gil-m">
                 <span>
                     Правила предоставления
                    <a style="color: RGB(189, 148, 87)" href="theme/site/new/docs/Pravila_predostavleniya_mikrozaimov.pdf" target="_blank"> микрозаймов</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox " name="service_reason" {if !$safe_mode}value="1"{else}value="0"{/if}/>
                <input type="checkbox"
                       class="custom-checkbox"
                       id="service_reason" {if !$safe_mode}value="1"{else}value="0"{/if} {if !$safe_mode}checked{/if}/>
                <label for="service_reason" class="check_box -gil-m">
                 <span>
                    В случае отказа по заявке, я хочу получить информацию о <a style="color: RGB(189, 148, 87)"
                                                                               href="theme/site/new/docs/{if !$safe_mode}find_reason_for_refusal.pdf{else}find_reason_for_refusal_safe.pdf{/if}"
                                                                               target="_blank">причине отказа</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox " name="service_sms" {if !$safe_mode}value="1"{else}value="0"{/if}/>
                <input type="checkbox"
                       class="custom-checkbox" 
                       id="service_sms" {if !$safe_mode}value="1"{else}value="0"{/if} {if !$safe_mode}checked{/if}/>
                <label for="service_sms" class="check_box -gil-m">
                 <span>
                    Услуга <a style="color: RGB(189, 148, 87)"
                                                                               href="theme/site/new/docs/sms-information.pdf"
                                                                               target="_blank">СМС-информирование</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="service_insurance" {if !$safe_mode}value="1"{else}value="0"{/if}/>
                <input type="checkbox"
                        class="custom-checkbox"
                       id="service_insurance" {if !$safe_mode}value="1"{else}value="0"{/if} {if !$safe_mode}checked{/if}/>
                <label for="service_insurance" class="check_box -gil-m">
                 <span>
                    согласен заключить договор страхования в соответствии
                    <a style="color: RGB(189, 148, 87)" href="theme/site/new/docs/страхование_кард.pdf" target="_blank">с правилами</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="obshie_usloviya" value="1"/>
                <input type="checkbox"
                       class="custom-checkbox js-need-check"
                       id="obshie_usloviya" value="1" {if !$safe_mode}checked{/if}/>
                <label for="obshie_usloviya" class="check_box -gil-m">
                 <span>
                    Общие условия
                    <a style="color: RGB(189, 148, 87)" href="theme/site/new/docs/общие_условия_договора_потребительского_займа.pdf" target="_blank"> потребительского микрозайма</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="vozvrat" value="1"/>
                <input type="checkbox"
                       style="width: 100px" class="custom-checkbox js-need-check"
                       id="vozvrat" value="1" {if !$safe_mode}checked{/if}/>
                <label for="vozvrat" class="check_box -gil-m">
                 <span>
                    Информация об условиях предоставления, использования
                    <a style="color: RGB(189, 148, 87)" href="theme/site/new/docs/информация_об_усовиях_предоставления_использования_и_возврата_займа.pdf" target="_blank"> и возврата потребительского микрозайма</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    {*}
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="cards_insurance" value="1" checked/>
                <input type="checkbox"
                       style="width: 100px" class="custom-checkbox js-need-check"
                       id="cards_insurance" value="1" {if !$safe_mode}checked{/if}/>
                <label for="cards_insurance" class="check_box -gil-m">
                 <span>
                    Правила
                    <a style="color: RGB(189, 148, 87)" href="theme/site/new/docs/страхование_кард.pdf" target="_blank"> страхования карт</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    {*}
    {if $order->contract}
        <div class="">
            <div class="form_row">
                <div class="check">
                    <input type="hidden" class="custom-checkbox" name="ind_usloviya" value="1"/>
                    <input type="checkbox" class="custom-checkbox js-need-check" id="ind_usloviya" value="1" {if !$safe_mode}checked{/if}/>
                    <label for="ind_usloviya" class="check_box -gil-m">
                        <a style="color: RGB(189, 148, 87)"
                           href="{$config->root_url}/preview/ind_usloviya_nl?contract_id={$order->contract->id}"
                           target="_blank">
                            <span>Индивидуальные условия</span>
                        </a>
                    </label>
                </div>
            </div>
        </div>
        {foreach $documents as $document}
            {if $document->type == 'ANKETA_PEP'}
                <div class="">
                    <div class="form_row">
                        <div class="check">
                            <input type="hidden" class="custom-checkbox" name="pep" value="1"/>
                            <input type="checkbox" class="custom-checkbox js-need-check" id="pep" value="1" {if !$safe_mode}checked{/if}/>
                            <label for="pep" class="check_box -gil-m">
                                <a class="pep" style="color: RGB(189, 148, 87)"
                                   href="/document/{$order->user_id}/{$document->id}?insurance=1"
                                   target="_blank">
                                    <span>Заявление на получение займа</span>
                                </a>
                            </label>
                        </div>
                    </div>
                </div>
            {/if}
        {/foreach}
    {/if}
</div>
