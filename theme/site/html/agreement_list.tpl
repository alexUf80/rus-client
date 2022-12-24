<div id="agreement_list" style="display:none" class="pr-3 pl-3">
    <ul>
    </ul>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="pers" value="1"/>
                <input type="checkbox"
                       class="custom-checkbox" {if !in_array($user->phone_mobile, ['79171018924', '79179400617'])}{/if}
                       id="pers" value="1"/>
                <label for="pers" class="check_box -gil-m">
                 <span>
                    Согласие на обработку
                    <a href="/files/about/soglasie_opd.pdf" target="_blank"
                       style="color: #4A2982">персональных данных</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="soglasie_pep" value="1"/>
                <input type="checkbox" class="custom-checkbox js-need-check" id="soglasie_pep" value="1"/>
                <label for="soglasie_pep" class="check_box -gil-m">
                 <span>
                     Соглашение
                    <a style="color: #4A2982" href="/files/about/soglashenie_o_ispolzovanii_pep.pdf" target="_blank"> АСП</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="pravila" value="1"/>
                <input type="checkbox" class="custom-checkbox js-need-check" id="pravila" value="1"/>
                <label for="pravila" class="check_box -gil-m">
                 <span>
                     Правила предоставления
                    <a style="color: #4A2982" href="/files/about/pravila_predostavleniya.pdf" target="_blank"> микрозаймов</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="service_reason" value="1"/>
                <input type="checkbox"
                       class="custom-checkbox"
                       id="service_reason" value="1"/>
                <label for="service_reason" class="check_box -gil-m">
                 <span>
                    В случае отказа по заявке, я хочу получить информацию о <a style="color: #4A2982"
                                                                               href="/files/about/prichina_otkaza.pdf"
                                                                               target="_blank">причине отказа</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="service_insurance" value="1"/>
                <input type="checkbox"
                       class="custom-checkbox"
                       id="service_insurance" value="1"/>
                <label for="service_insurance" class="check_box -gil-m">
                 <span>
                    согласен заключить договор страхования в соответствии
                    <a style="color: #4A2982" href="/files/about/Pravila_195_strahovanie_ot_ns.pdf" target="_blank">с правилами</a>
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
                       class="custom-checkbox"
                       id="obshie_usloviya" value="1"/>
                <label for="obshie_usloviya" class="check_box -gil-m">
                 <span>
                    Общие условия
                    <a style="color: #4A2982" href="/files/about/obshie_usloviya.pdf" target="_blank"> потребительского микрозайма</a>
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
                       style="width: 100px" class="custom-checkbox"
                       id="vozvrat" value="1"/>
                <label for="vozvrat" class="check_box -gil-m">
                 <span>
                    Информация об условиях предоставления, использования
                    <a style="color: #4A2982" href="/files/about/predostavlenie_vozvrat.pdf" target="_blank"> и возврата потребительского микрозайма</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    <div class="">
        <div class="form_row">
            <div class="check">
                <input type="hidden" class="custom-checkbox" name="cards_insurance" value="1"/>
                <input type="checkbox"
                       style="width: 100px" class="custom-checkbox"
                       id="cards_insurance" value="1"/>
                <label for="cards_insurance" class="check_box -gil-m">
                 <span>
                    Правила
                    <a style="color: #4A2982" href="/files/about/strahovanie_kart.pdf" target="_blank"> страхования карт</a>
                 </span>
                </label>
            </div>
        </div>
    </div>
    {if $order->contract}
        <div class="">
            <div class="form_row">
                <div class="check">
                    <input type="hidden" class="custom-checkbox" name="ind_usloviya" value="1"/>
                    <input type="checkbox" class="custom-checkbox" id="ind_usloviya" value="1"/>
                    <label for="ind_usloviya" class="check_box -gil-m">
                        <a style="color: #4A2982"
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
                            <input type="checkbox" class="custom-checkbox" id="pep" value="1"/>
                            <label for="pep" class="check_box -gil-m">
                                <a class="pep" style="color: #4A2982"
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