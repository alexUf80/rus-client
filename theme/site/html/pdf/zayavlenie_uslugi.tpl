<div>

</div>
<table align="center">
    <tr>
        <td width="100%" align="center"><strong>Заявление о предоставлении услуг</strong></td>
    </tr>
</table>
<div>

</div>
<div align="justify">
    Я, {$lastname|upper} {$firstname|upper} {$patronymic|upper}, 
    паспорт гражданина Российской Федерации: {$passport_serial} № {$passport_number}, 
    выдан: {$passport_issued|upper} {$passport_date}г., код подразделения: {$subdivision_code}, 
    адрес места жительства или пребывания: {$regaddress_full|escape}, 
    адрес фактического места жительства (почтовый): {$faktaddress_full|escape}, 
    номер мобильного телефона: {$phone_mobile}, 
    адрес электронной почты: {$email},
    настоящим, понимая значение своих действий и руководствуясь ими, прошу ООО МКК «Русзаймсервис» (далее - Общество) на основании данного заявления:
    
</div>

{foreach $operations as $operation}
    {if $operation->type == 'INSURANCE' || $operation->type == 'INSURANCE_BC'}
        <div align="justify">
            Я выражаю согласие на заключение договора страхования «САО ВСК» (далее – Страховщик), стоимостью {$operation->amount} рублей. ({$operation->amount|price_string|upper}). Я понимаю и согласен(на) с тем, что буду являться застрахованным лицом (либо действовать в отношении своих несовершеннолетних детей), а также выгодоприобретателем (а в случае смерти – мои наследники) по договору страхования.
        </div>
        <div align="justify">
            Я предварительно изучил(а) и согласен(а) с условиями страхования, изложенным в Правилах Страхования. Страхование является отдельной дополнительной услугой.
        </div>
        <div align="justify">
            Подписывая настоящее заявление, даю согласие на заключение договора страхования.
        </div>
        <div style="display:flex; justify-content: center; align-items: center;">
            <table border="0.5" cellpadding="5">
                <tbody>
                <tr>
                    <td style="width: 10%" align="center">{if $service_insurance}V{/if}</td>
                    <td style="width: 10%; text-align: center">Да</td>
                </tr>
                <tr>
                    <td style="width: 10%">{if !$service_insurance}V{/if}</td>
                    <td style="width: 10%; text-align: center">Нет</td>
                </tr>
                </tbody>
            </table>
        </div>
    {/if}
{/foreach}

{foreach $operations as $operation}
    {if $operation->type == 'BUD_V_KURSE'}
        <div align="justify">
            Я выражаю согласие на подключение услуги «СМС-информирование» стоимостью {$operation->amount} рублей. ({$operation->amount|price_string|upper}). 
        </div>
        <div align="justify"> 
            Я предварительно изучил(а) и согласен(а) с правилами предоставления услуги «СМС-информирование».
        </div>
        <div style="display:flex; justify-content: center; align-items: center;">
            <table border="0.5" cellpadding="5">
                <tbody>
                <tr>
                    <td style="width: 10%" align="center">{if $service_insurance}V{/if}</td>
                    <td style="width: 10%; text-align: center">Да</td>
                </tr>
                <tr>
                    <td style="width: 10%">{if !$service_insurance}V{/if}</td>
                    <td style="width: 10%; text-align: center">Нет</td>
                </tr>
                </tbody>
            </table>
        </div>
    {/if}
{/foreach}

{foreach $operations as $operation}
    {if $operation->type == 'DOCTOR'}
        <div align="justify">
            Я выражаю согласие на подключение услуги «КРЕДИТНЫЙ ДОКТОР» стоимостью {$operation->amount} рублей. ({$operation->amount|price_string|upper}).
        </div>
        <div align="justify"> 
            Я предварительно изучил(а) и согласен(а) с публичным договором-оферты об оказании услуги «кредитный доктор»
        </div>
        <div style="display:flex; justify-content: center; align-items: center;">
            <table border="0.5" cellpadding="5">
                <tbody>
                <tr>
                    <td style="width: 10%" align="center">{if $service_insurance}V{/if}</td>
                    <td style="width: 10%; text-align: center">Да</td>
                </tr>
                <tr>
                    <td style="width: 10%">{if !$service_insurance}V{/if}</td>
                    <td style="width: 10%; text-align: center">Нет</td>
                </tr>
                </tbody>
            </table>
        </div>
    {/if}
{/foreach}

<div align="justify">
    Мне известно, что Услуги предоставляется независимо от получения займа (не являются дополнительной услугой), не влияют на срок займа, процентную ставку или иные условия Договора микрозайма.
</div>
<div align="justify">
    Мне сообщено, что все претензии по объему, качеству и стоимости Услуги рассматриваются Обществом в соответствии с законодательством о защите прав потребителей.
</div>
<div align="justify">
    Я предоставляю Обществу акцепт (заранее данный акцепт) на списание денежных средств с моей банковской карты, которая использована мною при регистрации личного кабинета, без дополнительного распоряжения в счет погашения услуг, а также в  случае  ошибочного  зачисления  Кредитором денежных средств на мою банковскую карту, данные операции не могут быть мною оспорены.
</div>
<div align="justify">
    Я оповещен о том, что:
     - Имею право отказаться от услуги в течение тридцати календарных дней со дня выражения согласия на ее оказание посредством обращения к лицу, оказывающему такую услугу, с заявлением об отказе от такой услуги; 
     - Требовать от лица, оказывающего такую услугу, возврата денежных средств, уплаченных мною за оказание такой услуги, за вычетом стоимости части такой услуги, фактически оказанной мне до дня получения лицом, оказывающим такую услугу, заявления об отказе от такой услуги; 
     - Требовать от Общества возврата денежных средств, уплаченных мною третьему лицу за оказание такой услуги, за вычетом стоимости части такой услуги, фактически оказанной мне до дня получения третьим лицом заявления об отказе от такой услуги, при неисполнении таким третьим лицом обязанности по возврату денежных средств.
</div>
<br>
<br>
<table style="width: 100%">
    <tr style="width: 100%">
        <td style="width: 25%"></td>
        <td style="width: 25%"></td>
        <td style="width: 50%">электронный документ создан с использованием системы сайта https://rus-zaym.ru/<br>
            {$phone_mobile} {$docCreated|date} {$docCreated|time} {$accept_sms} {$docCreated|date} {$docCreated|time}
            {*}
            <br>СМС – код {$accept_code}, являющийся аналогом собственноручной <br>подписи, отправленный<br>на номер {$phone_mobile} введен
            верно<br>в {$created}
            {*}
        </td>
    </tr>
</table>