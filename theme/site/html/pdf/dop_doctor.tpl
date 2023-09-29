<div>

</div>

<table border="1">
    <tr style="width: 100%;">
        <td style="width: 28%;">
            <img src="{$config->root_dir}/theme/site/html/pdf/i/contract_qr2.png" width="250">
        </td>
        <td style="width: 36%" align="center">
            <h2>ПОЛНАЯ СТОИМОСТЬ ПОТРЕБИТЕЛЬСКОГО КРЕДИТА (ЗАЙМА) СОСТАВЛЯЕТ:
                {$base_percent*365}%
                ({($base_percent*365)|percent_string|upper}) ПРОЦЕНТОВ ГОДОВЫХ</h2>
        </td>
        <td style="width: 36%;" align="center">
            <h2>ПОЛНАЯ СТОИМОСТЬ ПОТРЕБИТЕЛЬСКОГО КРЕДИТА (ЗАЙМА) СОСТАВЛЯЕТ:
                {$return_amount_percents} РУБЛЕЙ
                {($return_amount_percents)|price_string|upper}</h2>
        </td>
    </tr>
</table>
<div>

</div>
<div align="justify">
    ООО МКК «Русзаймсервис», ОГРН 1197746729806, юридический адрес: 123112, г. Москва, наб. Пресненская, д. 12, этаж 29, помещение 2, комната А28, в лице Генерального директора Михалиной Карины Олеговны, действующей на основании Устава (далее - «<strong>Кредитор</strong>») адресованным
     {$lastname|upper} {$firstname|upper} {$patronymic|upper},  {$birth} года рождения, 
    паспорт гражданина Российской Федерации {$passport_series} {$passport_number}, код 
    подразделения {$subdivision_code}, выдан {$passport_issued}, дата выдачи {$passport_date}, 
    зарегистрированный по адресу (место нахождения): {$regaddress_full}
    (далее «Заемщик»), а каждый в отдельности – «Сторона», заключили настоящее соглашение
    № {$contract->number}-1 от {$contract->inssuance_date|date} к Договору потребительского займа № {$contract->number}
    от {$contract->inssuance_date|date} (далее - Договор) о нижеследующем:

    <ul>
    <li>Кредитор на условиях, определенных настоящим соглашением предоставляет Заемщику скидку в размере {$return_amount_percents} рублей {($return_amount_percents)|price_string|upper} копеек на уплату начисленных процентов по Договору.</li>
    <li>Условием предоставления скидки является своевременная оплата по Договору начисленных процентов. Своевременной оплатой считается оплата процентов в дату в соответствии с пунктом 2 Индивидуальных условий Договора.</li>
    <li>Скидка предоставляется в момент оплаты начисленных по договору процентов, при этом оплачиваются Кредитору в полном объеме за вычетом суммы скидки.</li>
    <li>Соглашение вступает в силу с {$contract->inssuance_date|date}, являясь неотъемлемой частью Договора.</li>

    </ul>

</div>

<table align="center">
    <tr>
        <td width="20%"></td>
        <td width="60%"><strong>Реквизиты и подписи сторон.</strong></td>
        <td width="10%"></td>
    </tr>
</table>
<div>

</div>
<table>
    <tr>
        <td width="50%"><u><strong>Кредитор:</strong></u></td>
        <td width="50%"><u><strong>Заемщик:</strong></u></td>
    </tr>
    <tr>
        <td width="50%"></td>
        <td width="50%"></td>
    </tr>
    <tr>
        <td width="50%">Общество с ограниченной ответственностью Микрокредитная компания «Русзаймсервис»</td>
        <td width="50%">{$lastname|upper} {$firstname|upper} {$patronymic|upper}</td>
    </tr>
    <tr>
        <td width="50%">Юр. адрес 123112, г. Москва, наб. Пресненская, д. 12, этаж 29, помещение 2, комната А28
        </td>
        <td width="50%">паспорт гражданина РФ: {$passport_serial}</td>
    </tr>
    <tr>
        <td width="50%">ОГРН: 1197746729806; ИНН/КПП</td>
        <td width="50%">Кем выдан: {$passport_issued|upper}</td>

    </tr>
    <tr>
        <td width="50%">9717088848/770301001;</td>
        <td width="50%">Дата выдачи: {$passport_date|date} код подр.: {$subdivision_code}</td>
    </tr>
    <tr>
        <td width="50%">р/с 40701810438000000421
        </td>
        <td width="50%">Адрес регистрации: {$regaddress_full}</td>
    </tr>
    <tr>
        <td width="50%">в ПАО СБЕРБАНК, г. Москва</td>
        <td width="50%">Телефон: {$contract->user_phone_mobile}</td>
    </tr>
    <tr>
        <td width="50%">к/с: 30101810400000000225</td>
        <td width="50%">E-mail: {$contract->user_email}</td>
    </tr>
    <tr>
        <td width="50%">БИК 044525225</td>
    </tr>
    <tr>
        <td width="50%">Адрес фактический подразделения: 123112, г. Москва, </td>
    </tr>
    <tr>
        <td width="50%">наб. Пресненская, д. 12, этаж 29, помещение 2, комната А28</td>
    </tr>
    <tr>
        <td width="50%">Сайт: https://rus-zaym.ru/ </td>
    </tr>
    <tr>
        <td width="50%">E-mail: info@rus-zaym.ru  </td>
    </tr>
    <tr>
        <td width="50%">Телефон 8 (977) 277-23-23</td>
    </tr>
    <tr>
        <td width="50%">
            <table>
                <tr>
                    <td style="position:relative">Подпись</td><td style="width:30px"><img src="{$config->root_dir}/theme/site/html/pdf/i/sign.png" style="width:30px; height: 30px;"></td><td style="width:100px"><img style="position:absolute; left:0" src="{$config->root_dir}/theme/site/html/pdf/i/stamp.png" style="width:100px; height: 100px;"></td><td> Дата {$contract->inssuance_date|date}</td>
                </tr>
            </table>
        </td>
        <td width="50%">
            электронный документ создан с использованием системы
            сайта https://rus-zaym.ru/<br>
            {$phone_mobile} {$sms_sent_date|date} {$sms_sent_date|time} {$contract->accept_code} {$contract->inssuance_date|date} {$contract->inssuance_date|time}
            {*}
            СМС – код  {$contract->accept_code}, являющийся аналогом собственноручной <br>
            подписи, отправленный<br>
            на номер {$phone_mobile} введен верно<br>
            в {$contract->inssuance_date|date} {$contract->inssuance_date|time}<br>
            {*}
        </td>
    </tr>
    <tr>
        <td width="50%">
            <table>
                <tr>
                    <td width="20%">М.П.</td>
                </tr>
            </table>
        </td>
    </tr>
    
    
</table>
<div>



