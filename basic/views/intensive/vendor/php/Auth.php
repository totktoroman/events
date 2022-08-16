<?php
	/**
	* Класс для авторизации
	*/
	class Auth{
		/**
		* @var string appKey - ключ авторизации для приложения, пишется в сессию
		*/
		public $appKey="default";
		public function __construct($appKey){
			session_start();
			$this->appKey=$appKey;
		}
		/**
		* Метод производит проверку авторизации и если данные введены верно пишет в сессию данные и возвращает true
		* @param login - логин
		* @param password - пароль
		* @param DBHandler - ссылка на хандлер подключения к бд
		* @return bool - успешно или не успешно прошла авторизация
		*/
		public function checkLogin($login,$password,$handler){
			if($handler->getNumRow('imi_app_auth',array("imi_app_auth_login"=>$login,"imi_app_auth_password"=>md5($password),'imi_app_auth_key'=>$this->appKey))>0){
				$this->authUser();
				return true;
			}
			else{
				return false;
			}
		}
		/**
		* Метод пишет в сессию данные об авторизации
		*/ 
		public function authUser(){
			if($_SESSION[$this->appKey.'_logged']!=1){
				$_SESSION[$this->appKey.'_logged']=1;
			}
		}
		/**
		* Метод проверяет авторизацию пользователя
		* @return bool -  true если авторизован, иначе false
		*/
		public function check(){
			if($_SESSION[$this->appKey.'_logged']==1){
				return true;
			}
			else{
				return false;
			}
		}
		/**
		* Метод производит разлогинивание и перекидывает на страницу логина
		* @param string - куда перекинуть
		*/
		public function logOut($link){
			$_SESSION[$this->appKey.'_logged']=0;
			header("Location:".$link);
		}
	}
?>