<?php 

require 'vendor/autoload.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;


require_once "vendor/autoload.php"; //Объект PHPMailer
$mail = new PHPMailer; //Имя и электронный адрес отправителя 
$mail->From = "nikmolios@gmail.com"; 
$mail->FromName = "Nikita"; // Имя и электронный адрес получателя
$mail->addAddress("wilbertmolchun@gmail.com", "Poluchatel");//Имя получателя необязательно
$mail->addAddress("wilbertmolchun@gmail.com"); //Адрес на который получатель будет отвечать 
$mail->addReplyTo("reply@yourdomain.com", "Ответ"); //CC и BCC 
$mail->addCC("cc@example.com"); 
$mail->addBCC("bcc@example.com"); //Отправка HTML или обычного текста 
$mail->isHTML(true); 
$mail->Subject = "Тема письма"; 
$mail->Body = "<i>Тело письма в HTML</i>";
$mail->AltBody = "Это текстовая версия письма"; 
if(!$mail->send()) 
{
echo "Ошибка: " . $mail->ErrorInfo; 
} 
else { echo "Сообщение успешно отправлено"; 
}
if(!$mail->send()) 
{ 
echo "Ошибка: " . $mail->ErrorInfo; 
} 
else 
{ 
echo "Сообщение успешно отправлено"; 
}