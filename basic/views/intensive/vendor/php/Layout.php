<?php
	/**
	* Класс для отображения страниц
	*/
	class Layout{
		/**
		* @var array cssParam - набор подключаемых css
		*/
		public $cssParam=array('/projects/vendor/css/bootstrap.min.css','/projects/vendor/css/bootstrap-theme.min.css');
		/**
		* @var string title -имя страницы
		*/
		public $title="Default page";
		/**
		* @var string indexPage - стартовая страница ресурса
		*/
		public $indexPage="index.php";
		/**
		*@var array menu - навигационное меню
		*/
		public $menu=array(array("link"=>"/","title"=>"Главная"));
		/**
		* Метод возвращает строку с css из списка
		*/
		public function loadCSS(){
			$css="";
			if (count($this->cssParam)>0){
				foreach($this->cssParam as $item){
					$css=$css.'<link href="'.$item.'" rel="stylesheet">';	
				}	
			}
			return $css;
		}
		/**
		* Метод формирует хидер
		*/
		public function generateHeader(){
			$layout=$this;
			require_once($_SERVER['DOCUMENT_ROOT']."/projects/vendor/php/views/standard_header.php");
		}
		/**
		* Метод формирует футер
		*/
		public function generateFooter(){
			$layout=$this;
			require_once($_SERVER['DOCUMENT_ROOT']."/projects/vendor/php/views/standard_footer.php");
		}
	}
?>