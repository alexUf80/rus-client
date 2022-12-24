<div id="agreement_list" style="display:none" class="pr-3 pl-3">
    <ul>
        {*}
        <li>
            <a href="{$config->root_url}/files/docs/pravila_predostavleniya_mikrozaymov.pdf" target="_blank">
                <span>Правила предоставления микрозаймов</span>
            </a>
        </li>

        <li>
            <a href="{$config->root_url}/files/docs/soglashenie_o_ispolzovanii_pep.pdf" target="_blank">
                <span>Соглашение о АСП</span>
            </a>
        </li>
        {*}
    </ul>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="service_insurance" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="service_insurance" value="1" checked/>
              <label for="service_insurance" class="check_box -gil-m">
                 <span>
                    согласен заключить договор страхования в соответствии
                    <a class="text-success" href="/files/about/Pravila_195_strahovanie_ot_ns.pdf" target="_blank">с правилами</a>
                 </span>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="service_reason" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="service_reason" value="1" checked/>
              <label for="service_reason" class="check_box -gil-m">
                 <span>
                    В случае отказа по заявке, я хочу получить информацию о причине отказа
                 </span>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="soglasie_pep" value="1" />
              <input type="checkbox" class="custom-checkbox js-need-check"  id="soglasie_pep" value="1" checked/>
              <label for="soglasie_pep" class="check_box -gil-m">
                 <span>
                    <a class="text-success" href="/files/docs/soglashenie_o_ispolzovanii_pep.pdf" target="_blank">Соглашение о АСП</a>
                 </span>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="soglasie_otkaz" value="1" />
              <input type="checkbox" class="custom-checkbox js-need-check"  id="soglasie_otkaz" value="1" checked/>
              <label for="soglasie_otkaz" class="check_box -gil-m">
                 <span>
                    <a class="text-success" href="/files/docs/Pravila_okazaniya_uslugi_Uznaj_prichinu_otkaza.pdf" target="_blank">Правила оказания услуги "Узнай причину отказа"</a>
                 </span>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="soglasie_opd" value="1" />
              <input type="checkbox" class="custom-checkbox js-need-check"  id="soglasie_opd" value="1" checked/>
              <label for="soglasie_opd" class="check_box -gil-m">
                 <span>
                    <a class="text-success" href="/files/docs/Soglasie_na_OPD.pdf" target="_blank">Согласие на ОПД</a>
                 </span>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="soglasie_ki" value="1" />
              <input type="checkbox" class="custom-checkbox js-need-check"  id="soglasie_ki" value="1" checked/>
              <label for="soglasie_ki" class="check_box -gil-m">
                 <span>
                    <a class="text-success" href="/files/docs/Soglasie_na_poluchenie_KI.pdf" target="_blank">Согласие на получение КИ</a>
                 </span>
              </label>
            </div>
        </div>
    </div>

<!--
    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="service_reason" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="service_reason" value="1" checked />
              <label for="service_reason" class="check_box -gil-m">
                 <span>
                    В случае отказа по заявке, я хочу получить информацию о причине отказа
                 </span>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="service_sms" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="service_sms" value="1" checked />
              <label for="service_sms" class="check_box -gil-m">
                 <span>
                    Согласен на подключение услуги
                    <a class="text-success" href="/files/about/pravila_bud_v_kurse.pdf" target="_blank">"Будь в курсе"</a>
                 </span>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="docs_psns" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="docs_psns" value="1" checked />
              <label for="docs_psns" class="check_box -gil-m">
                 <a href="{$config->root_url}/files/docs/Pravila_strahovaniya_ot_neschastnyh_sluchaev.pdf" target="_blank">
                    <span>Правила страхования от несчастных случаев</span>
                </a>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="docs_psbk" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="docs_psbk" value="1" checked />
              <label for="docs_psbk" class="check_box -gil-m">
                 <a href="{$config->root_url}/files/docs/Pravila_strahovanie_bankovskih_kart.pdf" target="_blank">
                    <span>Правила страхование банковских карт</span>
                </a>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="docs_ppubk" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="docs_ppubk" value="1" checked />
              <label for="docs_ppubk" class="check_box -gil-m">
                 <a href="{$config->root_url}/files/docs/Pravila_predostavleniya_uslugi_byt_v_kurse.pdf" target="_blank">
                    <span>Правила предоставления услуги быть в курсе</span>
                </a>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="docs_ppz" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="docs_ppz" value="1" checked />
              <label for="docs_ppz" class="check_box -gil-m">
                 <a href="{$config->root_url}/files/docs/Pravila_predostavleniya_zajmov.pdf" target="_blank">
                    <span>Правила предоставления займов</span>
                </a>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="docs_oudpz" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="docs_oudpz" value="1" checked />
              <label for="docs_oudpz" class="check_box -gil-m">
                 <a href="{$config->root_url}/files/docs/Obshchie_usloviya_dogovora_potrebitelskogo_zajma.pdf" target="_blank">
                    <span>Общие условия договора потребительского займа</span>
                </a>
              </label>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="docs_iupiv" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="docs_iupiv" value="1" checked />
              <label for="docs_iupiv" class="check_box -gil-m">
                 <a href="{$config->root_url}/files/docs/INFORMACIYA_OB_USLOVIYAH_PREDOSTAVLENIYA_ISPOLZOVANIYA_I_VOZVRATA.pdf" target="_blank">
                    <span>Информация об условиях предоставления, использования и возврата</span>
                </a>
              </label>
            </div>
        </div>
    </div>
-->

<!--     <div class="">
        <div class="form_row">
            <div class="check">
              <input type="hidden" class="custom-checkbox" name="docs_ppm" value="1" />
              <input type="checkbox" class="custom-checkbox"  id="docs_ppm" value="1" checked />
              <label for="service_sms" class="check_box -gil-m">
                 <a href="{$config->root_url}/files/docs/pravila_predostavleniya_mikrozaymov.pdf" target="_blank">
                    <span>Правила предоставления микрозаймов</span>
                </a>
              </label>
            </div>
        </div>
    </div>  -->



</div>