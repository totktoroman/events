<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;


class User extends ActiveRecord implements \yii\web\IdentityInterface
{
     public static function tableName(){
         return "user";
     } //обращение к таблице user
    
    public function rules(){
        // правила модели
        return [ 
            [['user_login','user_password'], 'required'],
            [['user_status_id'], 'default','value'=>2],
          ];
     }
    
    //  меняется лэйбл переменной 
    //  public function attributeLabels(){
    //      return [
    //         'user_login' => "Логин",
    //      ];
    //  }
    
		
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
      //  return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
      return User::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /*
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }
        */

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        /*foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
         }*/

        return User::find()->where("user_login='".$username."'")->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->user_id;
    }

    public function getLogin(){
        return $this->user_login;
    }
    
    public function getStatus()
    {
        return $this->user_status_id;
    }

    public function getInfo(){
        return UserInfo::find()->where(['user_id' => $this->user_id])->one()->getInfo();
    }

    public static function getMail($id){
        return UserInfo::find()->where(['user_id' => $id])->one()->user_email;
    }

    public function getStatusTitle(){
     if($this->user_status_id == 1){
            return "ADMINISTRATOR"; 
     }
     if($this->user_status_id == 2){
            return "USER"; 
     }
     if($this->user_status_id == 3){
            return "ORGANIZATOR"; 
     }
     if($this->user_status_id == 5){
         return "INACTIVE"; 
     }
        
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
       // return $this->authKey;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        //return $this->authKey === $authKey;
        return true;
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->user_password === $password;
    }
    
    function actionIndex(){
		$user=Yii::$app->user->identity->user_id;
	}
    
}
