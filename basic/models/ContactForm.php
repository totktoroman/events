<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Mailer;
/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            // echo '<br><br><br><br>';
            // echo $this->name.'<br>';
            // echo $this->email.'<br>';
            // echo $this->subject.'<br>';
            // echo $this->body.'<br>';
            
            // $model = new Mailer();
            // //$model->sendTestMail($this->email, $this->subject, $this->body);
            // $x = Mailer::sendTestMail($this->email, $this->subject, $this->body);
            // if($x === true){
            //     echo '<b>111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111Ушло111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111</b>';
            // }
            // else{
            //     echo $x;
            // }
            
            $params = Yii::$app->params['adminEmail'];
            Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setSubject($this->subject)
            ->setTextBody($this->email.' '.$this->body)
            ->send();

           
           return true;
        }
       
     
        return false;
    }
}
