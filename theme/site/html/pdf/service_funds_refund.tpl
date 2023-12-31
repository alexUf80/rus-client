<div>
</div>
<table width="100%">
    <tr>
        <td width="50%"></td>
        <td width="50%"  align="right"><h4>
            {$lastname} {$firstname} {$patronymic}
            <br><br>Генеральному директору 
            <br>ООО МКК «Русзаймсервис»
            <br>Михалиной К. О.
        </h4></td>
    </tr>
</table>
<div>
<br><br>
</div>
<table align="center">
    <tr>
        <td width="100%" align="center"><strong>Заявление<br>
            об отказе от исполнения договора оказания услуг и возврате денежных средств
            </strong>
        </td>
    </tr>
</table>

<div>
    <br><br>
    <p>&nbsp;&nbsp;&nbsp;&nbsp; Между мною {$lastname} {$firstname} {$patronymic} (паспорт гражданина РФ: {$passport_serial}) и Обществом с ограниченной ответственностью Микрокредитная компания «Русзаймсервис» (далее – Общество) был заключен Договор микрозайма № {$number}  от {$inssuance_date|date}.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp; Подписывая Заявление о предоставлении потребительского займа, </p>
    {foreach $sms_amounts as $sms_amount}
        {if $sms_amount}
            <p>&nbsp;&nbsp;&nbsp;&nbsp; Мною было выражено согласие на подключение услуги «СМС-информирование» стоимостью {$sms_amount} {*}({$sms_amount|price_string|upper}){*}.</p>
        {/if}
    {/foreach}
    {foreach $inssuance_amounts as $inssuance_amount}
        {if $inssuance_amount}
            <p>&nbsp;&nbsp;&nbsp;&nbsp; Мною было выражено согласие на заключение договора страхования «САО ВСК» стоимостью {$inssuance_amount} РУБЛЕЙ 00 КОПЕЕК {*}({$inssuance_amount|price_string|upper}){*}.</p>
        {/if}
    {/foreach}
    <p>&nbsp;&nbsp;&nbsp;&nbsp; На момент составления заявления данные услуги Мною не использованы.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp; В соответствии с п. 2.5 ст. 7 ФЗ № 353 "О потребительском кредите (займе)" от 21.12.2013.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp; <strong>Прошу:</strong></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1.	Произвести возврат денежных средств за вышеуказанные услуги в счет погашения действующей задолженности.</p>
    <br>
    <p>{$create_date|date}</p>
    <br>
    {*}
    <p>Подпись/{$lastname} {$firstname} {$patronymic}</p>
    {*}
    <p>&nbsp;&nbsp;&nbsp;&nbsp;СМС – код  {$asp}, являющийся аналогом собственноручной <br>
        подписи, отправленный<br>
        на номер {$phone_mobile} введен верно<br></p>
</div>
