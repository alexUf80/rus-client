<?php
error_reporting(-1);
ini_set('display_errors', 'On');

if(strlen($_POST['phone']) == 16 ){
    $to  = "<info@mkkbf.ru>"; 

    $subject = "Письмо с сайта bf-test.ru"; 

    $message = 'Имя - '. $_POST['name'] .'<br>Почта - '. $_POST['email'] .'<br>Телефон - '. $_POST['phone'] .'<br>Сообщение - '. $_POST['message'];

    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    $headers .= "From: От кого письмо <info@mkkbf.ru>\r\n"; 
    $headers .= "Reply-To: reply-to@example.com\r\n"; 

    mail($to, $subject, $message, $headers); 

    echo 1;
}else {
    echo 0;
}