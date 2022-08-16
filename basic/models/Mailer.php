<?php

namespace app\models;
use yii\base\Exception;
/**
* mailer
* 
* Класс для отправки писем через SMTP с авторизацией
* Может работать через SSL протокол
* Тестировалось на почтовых серверах yandex.ru, mail.ru и gmail.com
* 
* @author Ipatov Evgeniy <admin@ipatov-soft.ru>
* @version 1.0
*/
class Mailer extends \Yii\base\Model{

    /**
    * 
    * @var string $smtp_username - логин
    * @var string $smtp_password - пароль
    * @var string $smtp_host - хост
    * @var string $smtp_from - от кого
    * @var integer $smtp_port - порт
    * @var string $smtp_charset - кодировка
    *
    */   
    // public static $smtp_username = 'events.bsu.ru';
    // public static $smtp_password = 'ygalazmmjxdrhaek';
    // //public static $smtp_password = 'eventspassword';
    // public static $smtp_host = 'ssl://smtp.yandex.ru';
    // public static $smtp_name = 'events.bsu.ru';
    // public static $smtp_from = 'events.bsu.ru@yandex.ru';
    // public static $smtp_port = '993';
    // public static $smtp_charset = 'UTF-8';
    // public static $headers = '';

    public static $smtp_username = 'events.bsu.ru@gmail.com';
    public static $smtp_password = 'tgqieookwjdrjgms';
    //public static $smtp_password = 'eventspassword';
    public static $smtp_host = 'ssl://smtp.gmail.com';
    public static $smtp_name = 'events.bsu.ru@gmail.com';
    public static $smtp_from = 'events.bsu.ru@gmail.com';
    public static $smtp_port = '465';
    public static $smtp_charset = 'UTF-8';
    public static $headers = '';
    // 'class' => 'Swift_SmtpTransport',
    // 'host' => 'smtp.gmail.com',
    // 'username' => 'events.bsu.ru@gmail.com',
    // 'password' => 'tgqieookwjdrjgms',
    // 'port' => '465',
    // 'encryption' => 'ssl',
    
    public function __construct(/*$smtp_username, $smtp_password, $smtp_host, $smtp_from, $smtp_port = 25, $smtp_charset = "utf-8"*/) {
        /*$this->smtp_username = $smtp_username;
        $this->smtp_password = $smtp_password;
        $this->smtp_host = $smtp_host;
        $this->smtp_from = $smtp_from;
        $this->smtp_port = $smtp_port;
        $this->smtp_charset = $smtp_charset;*/
    }
    
    /**
    * Отправка письма
    * 
    * @param string $mailTo - получатель письма
    * @param string $subject - тема письма
    * @param string $message - тело письма
    * @param string $headers - заголовки письма
    *
    * @return bool|string В случаи отправки вернет true, иначе текст ошибки    *
    */
    private static function send($mailTo, $subject, $message) {
        $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
        $contentMail .= 'Subject: =?' . self::$smtp_charset . '?B?'  . base64_encode($subject) . "=?=\r\n";
        $contentMail .= 'X-Priority: 3 (Normal)'."\r\n";
        $contentMail .= "To: =?".self::$smtp_charset."?B?Получатель?=<".$mailTo.">\r\n";
        $contentMail .= "MIME-Version: 1.0\r\nContent-type: text/html; charset=".self::$smtp_charset."\r\nFrom: ".self::$smtp_name." <".self::$smtp_from.">\r\n";
        $contentMail .= "Content-Transfer-Encoding : 8bit\r\n";
        $contentMail .= "\r\n".$message;
        
        try {
            if(!$socket = @fsockopen(self::$smtp_host, self::$smtp_port, $errorNumber, $errorDescription, 30)){
                throw new Exception($errorNumber.".".$errorDescription);
            }
            if (!self::_parseServer($socket, "220")){
                throw new Exception('Connection error');
            }
			
			$server_name = 'bsu.ru'; //$_SERVER["SERVER_NAME"];
            fputs($socket, "HELO $server_name\r\n");
            if (!self::_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: HELO');
            }
            
            fputs($socket, "AUTH LOGIN\r\n");
            if (!self::_parseServer($socket, "334")) {
                fclose($socket);
                throw new Exception('Autorization error 334');
            }
			
			
            
            fputs($socket, base64_encode(self::$smtp_username) . "\r\n");
            if (!self::_parseServer($socket, "334")) {
                fclose($socket);
                throw new Exception('Autorization error User_name');
            }
            
            fputs($socket, base64_encode(self::$smtp_password) . "\r\n");
            if (!self::_parseServer($socket, "235")) {
                fclose($socket);
                throw new Exception('Autorization error password');
            }
			
            fputs($socket, "MAIL FROM: <".self::$smtp_username.">\r\n");
            if (!self::_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: MAIL FROM');
            }
            
			$mailTo = ltrim($mailTo, '<');
			$mailTo = rtrim($mailTo, '>');
            fputs($socket, "RCPT TO: <" . $mailTo . ">\r\n");     
            if (!self::_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: RCPT TO');
            }
            
            fputs($socket, "DATA\r\n");     
            if (!self::_parseServer($socket, "354")) {
                fclose($socket);
                throw new Exception('Error of command sending: DATA');
            }
            
            fputs($socket, $contentMail."\r\n.\r\n");
            if (!self::_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception("E-mail didn't sent");
            }
            
            fputs($socket, "QUIT\r\n");
            fclose($socket);
        } catch (Exception $e) {
            return false;
            return  $e->getMessage();
        }
        
        return true;
    }
    
    private static function _parseServer($socket, $response) {
        while (@substr($responseServer, 3, 1) != ' ') {
            if (!($responseServer = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($responseServer, 0, 3) == $response)) {
            return false;
        }
        return true;
        
    }
    
    public static function sendTestMail($email, $subject, $message){
        // $subject = 'test mail';
        // $message = "Первая попытка отправить письмо на почту";
        return self::send($email, $subject, $message);
    }

    public static function sendRegisterDecline($email, $params){
        $subject = 'Заявка на мероприятие отклонена';
        $model = $params['model'];
        $message = 'Заявка на мероприятие "'.$model->title.'", запланированное на '.$model->event_start_date.' отклонена'; 
        self::send($email, $subject, $message);

    }
    public static function sendRegisterAccept($email, $params){
        $subject = 'Заявка на мероприятие одобрена';
        $model = $params['model'];
        $message = 'Заявка на мероприятие "'.$model->title.'", запланированное на '.$model->event_start_date.' одобрена'; 
        self::send($email, $subject, $message);

    }

    public static function sendEventUpdated($email, $params){
        $subject = 'Мероприятие обновлено';
        $model = $params['model'];
        $message = 'Параметры вашего мероприятия "'.$model->title.'" были обновлены:<br>'; //title, model
        $message .= ($model->getAttributeLabel('e_time').': '.$model->e_time.'<br>');
        $message .= ($model->getAttributeLabel('e_end').': '.$model->e_end.'<br>');
        $message .= ($model->getAttributeLabel('r_time').': '.$model->r_time.'<br>');
        $message .= ($model->getAttributeLabel('title').': '.$model->title.'<br>');
        $message .= ($model->getAttributeLabel('location').': '.$model->location.'<br>');
        $message .= ($model->getAttributeLabel('person').': '.$model->person.'<br>');
        $message .= ($model->getAttributeLabel('phone').': '.$model->phone.'<br>');
        $message .= ($model->getAttributeLabel('position').': '.$model->position.'<br>');
        $message .= ($model->getAttributeLabel('office').': '.$model->office.'<br>');
        $message .= ($model->getAttributeLabel('faculty').': '.$model->faculty.'<br>');
        $message .= ($model->getAttributeLabel('screen').': '.$model->screen.'<br>');
        $message .= ($model->getAttributeLabel('projector').': '.$model->projector.'<br>');
        $message .= ($model->getAttributeLabel('notebook').': '.$model->notebook.'<br>');
        $message .= ($model->getAttributeLabel('microphone').': '.$model->microphone.'<br>');
        self::send($email, $subject, $message);
    }
    

    public static function sendRequestDeclined($email, $params){
        $subject = 'Заявка на мероприятие отклонена';
        $model = $params['model'];
        $reason = $params['reason'];
        $message = 'Заявка на мероприятие "'.$model->title.'", запланированное с '.$model->e_time.' по '.$model->e_end.' отклонена по следующей причине:<br>'.$reason; //title, reason
        self::send($email, $subject, $message);
    }


    public static function sendRequestCreate($email, $params){
        $models = $params['models'];
        $code = $params['code'];
        $subject = 'Заявка на мероприятие успешно создана.';
        $message = 'Заявка на мероприятие "'.$models[0]->title.'" успешно создана. <br>
                    При изменении статуса заявки вам будет выслано письмо на этот адрес электронной почты. <br>
                    PDF бланка заявления можно получить тут: <a href="http://rooms.bsu.ru/web/site/get-pdf?id='.$models[0]->id.'">PDF</a><br>';
        foreach ($models as $key => $value) {
            $message .= 'Отменить заявку на мероприятие, запланированное на '.$value->e_time.' можно по следующей ссылке: <a href="http://rooms.bsu.ru/web/site/del-request?code='.$value->getCode().'">Отменить мероприятие</a><br>';
        }
        /*print_r($message);
        exit(0);*/
        self::send($email, $subject, $message);
    }

    public static function sendEventDelete($email, $params){
        $subject = 'Заявка на мероприятие удалена';
        $model = $params['model'];
        $message = 'Заявка на мероприятие "'.$model->title.'", запланированное на '.$model->e_time.', удалена Вами '.date('Y-m-d H:i:s'); //title, reason
        self::send($email, $subject, $message);
    }    

    public static function sendEventDisapproved($email, $params){
        $subject = 'Мероприятие отменено';
        $model = $params['model'];
        $reason = $params['reason'];
        $message = 'Мероприятие "'.$model->title.'", запланированное на '.$model->e_time.', отменено по следующей причине:<br>'.$reason; //title, reason
        self::send($email, $subject, $message);
    }

    public static function sendRequestApproved($email, $params){
        $subject = 'Заявка на мероприятие подтверждена';
        $model = $params['model'];
        $message = 'Мероприятие "'.$model->title.'", запланированное на '.$model->e_time.', подтверждено'; //title, reason
        self::send($email, $subject, $message);
    }

    public static function sendAdminNotification($params){
        $models = $params['models'];
        $subject = 'Подана заявка на мероприятие';
        $message = 'Параметры мероприятия:<br>';
        $model = $models[0];
        $message .= ($model->getAttributeLabel('title').': '.$model->title.'<br>');
        $message .= ($model->getAttributeLabel('r_time').': '.$model->r_time.'<br>');
        $message .= '<br>';
        $message .= 'Даты мероприятия:<br>';
        foreach ($models as $key => $value) {
            $message .= ($value->getAttributeLabel('e_time').': '.$value->e_time.'<br>');
        }
        $message .= '<br>';
        $message .= ($model->getAttributeLabel('e_end').': '.$model->e_end.'<br>');
        $message .= ($model->getAttributeLabel('location').': '.$model->location.'<br>');
        $message .= ($model->getAttributeLabel('person').': '.$model->person.'<br>');
        $message .= ($model->getAttributeLabel('phone').': '.$model->phone.'<br>');
        $message .= ($model->getAttributeLabel('position').': '.$model->position.'<br>');
        $message .= ($model->getAttributeLabel('office').': '.$model->office.'<br>');
        $message .= ($model->getAttributeLabel('faculty').': '.$model->faculty.'<br>');
        $message .= ($model->getAttributeLabel('screen').': '.$model->screen.'<br>');
        $message .= ($model->getAttributeLabel('projector').': '.$model->projector.'<br>');
        $message .= ($model->getAttributeLabel('notebook').': '.$model->notebook.'<br>');
        $message .= ($model->getAttributeLabel('microphone').': '.$model->microphone.'<br>');
        //self::send('libr-rooms@yandex.ru', $subject, $message);
    }
}