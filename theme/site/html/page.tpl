{capture name='page_scripts'}


{/capture}

{capture name='page_styles'}
    
{/capture}

<main class="main">
  <div class="section ">
    <div class="wrapper">
      <div class="section_row row">

        
        <div class="col-lg-12">
          {* <h1>{$page->name}</h1> *}
          <section class="breadcrumbs">
            <a href="" class="breadcrumbs__link breadcrumbs__link_home">Главная</a>
            <span class="breadcrumbs__separator"> / </span>
            <a class="breadcrumbs__link breadcrumbs__link_active">{$page->name|escape}</a>
          </section>
            {if $page->url == 'documents'}
            <h2>Правоустанавливающие документы</h2>
            <div class="row">
              <div class="col-md-6">
                  <ul class="docs_list">				
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/standart_zashiti.pdf" target="_blank">Базовый стандарт защиты прав и интересов физических и юридических лиц - получателей финансовых услуг</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/baza_mkk.pdf" target="_blank">Базовый стандарт совершения микрофинансовой организацией операций на финансовом рынке</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/mfo_bank_rossii.pdf" target="_blank">Информационная памятка Банка России о МФО</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/sro.pdf" target="_blank">СРО ООО МКК БФ</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/soglashenie_asp.pdf" target="_blank">Соглашение об использовании Аналога собственноручной подписи</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/bank_card_insurance_rules.pdf" target="_blank">Правила страхования банковских карт</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/rules_of_combined_accident_and_disease_insurance.pdf" target="_blank">Правила комбинированного страхования от несчастных случаев и болезней</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/information_about_the_conditions_for_the_provision_use_and_return_of_consumer_microloans.pdf" target="_blank">Информация об условиях предоставления, использования и возврата потребительского микрозайма</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/recommendations_to_the_client_on_protecting_information_from_the_effects_of_malicious_codes.pdf" target="_blank">Рекомендации клиенту по защите информации от воздействия вредоносных кодов в целях противодействия незаконным фонансовым операциям</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/payment_security_policy.pdf" target="_blank">Политика безопасности платежей</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/ogrn_inn_ooo_mkk_bf.pdf" target="_blank">ОГРН ИНН ООО МКК БФ</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/information_about_the_structure_and_composition_of_the_participants_of_the_microfinance_organization.pdf" target="_blank">Информация о структуре и составе участников микрофинансовой организации ООО МКК БФ</a></li>
                      <li class="docs_list_item"><a class="docs_list_link" href="/files/about/instructions_for_repayment_of_the_loan.pdf" target="_blank">Инструкция по погашению займа</a></li>
                  </ul>
                </div>
                <div class="col-md-6">
                    <ul class="docs_list">
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/politica_OPD.pdf" target="_blank">Политика в области обработки персональных данных</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/egrul.pdf" target="_blank">ЕГРЮЛ</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/requisites_barents.pdf" target="_blank">Реквизиты ООО МКК Баренц Финанс</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/ustav.pdf" target="_blank">Устав ООО МКК Баренц Финанс</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/baza_po_riskam.pdf" target="_blank">Базовый стандарт по управлению рисками микрофинансовых организаций</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/acceptance_and_rejection_rules.pdf" target="_blank">Правила предоставления услуги приема отказа</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/rules_for_granting_microloans.pdf" target="_blank">Правила предоставления потребительских займов</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/general_terms_of_a_consumer_microloan.pdf" target="_blank">Общие условия договора потребительского микрозайма</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/certificate_of_entry_of_information_into_the_state_register.pdf" target="_blank">Свидетельство о внесении сведений в государственный реестр МФО</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/rules_for_granting_credit_holidays.pdf" target="_blank">Правила предоставления кредитных каникул</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/memo_for_mobilized_and_participants.pdf" target="_blank">Памятка для мобилизованных и участников СВО</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/information_provided_to_the_recipient_of_the_financial_service.pdf" target="_blank">Информация предоставляемая получателю финансовой услуги</a></li>
                        <li class="docs_list_item"><a class="docs_list_link" href="/files/about/information_on_the_right_of_consumers_of_financial_services_to_appeal_to_the_financial_commissioner.pdf" target="_blank">Информация о праве потребителей финансовых услуг на обращение к финансовому уполномоченному</a></li>                   
                    </ul>
                </div>
            </div>
            {/if}

          <div>
              {$page->body}
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
