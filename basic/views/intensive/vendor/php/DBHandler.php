<?php
class DBHandler{
	/*Настройки*/
	public $host="localhost";
	public $dbname="imi.bsu.ru";
	//public $user="imi.bsu.ru";
	//public $password="MFe42sEkRq";
	public $user="root";
	public $password="root";
	public $db_prefix="";
	public $DBH;
	public function __construct(){
		$this->DBH=new PDO("mysql:host=".$this->host.";dbname=".$this->dbname,$this->user,$this->password);
		$this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->DBH->prepare("set character_set_client='utf8'")->execute();
		$this->DBH->prepare("set character_set_results='utf8'")->execute();
		$this->DBH->prepare("set collation_connection='utf8_general_ci'")->execute();	
	}	
	function getDBH(){
			return $this->DBH;
		}
		function setQuery($query){
			$this->query=$query;
		}
		function execute($data=false){
			if ($this->query!=""){
				$STH=$this->DBH->prepare($this->query);
				if ($data){
					$STH->execute($data);					
				}				
				else{
					$STH->execute();
				}
				return $STH;
			}
			else{
				return false;
			}
		}
		function fetchSTH($STHexecuted){
			if ($STHexecuted){
				$STHexecuted->setFetchMode(PDO::FETCH_ASSOC);
				$result=array();
				while ($row=$STHexecuted->fetch()){
					array_push($result,$row);
				}			
				return $result;
			}
			else{				
				return false;
			}	
		}		
		function getNumRow($table,$data=false){
			//Функция вернёт количество кортежей в таблице
			if (!$data){
				$this->setQuery('SELECT COUNT(*) AS numRow FROM `'.$this->db_prefix.$table.'`');	
			}
			else{			
				$str="SELECT COUNT(*) AS numRow FROM `".$this->db_prefix.$table."` WHERE TRUE ";
				$data1=array();	
				foreach(array_keys($data) as $keys){
					$str=$str." AND `".$keys."` = ?";
					array_push($data1,$data[$keys]);
				}
				$this->setQuery($str);
			}			
			$STH=$this->execute($data1);						
			if ($STH){
				$STH->setFetchMode(PDO::FETCH_ASSOC);
				$result=$STH->fetch();							
				return $result['numRow'];
			}
			else{				
				return 0;
			}		
		}
		function getArrayFromQuery($query){
			//получаем ассоциативный массив из запроса
			$this->setQuery($query);
			$STH=$this->execute();
			if ($STH){
				$STH->setFetchMode(PDO::FETCH_ASSOC);
				$result=array();
				while ($row=$STH->fetch()){
					array_push($result,$row);
				}			
				return $result;
			}
			else{				
				return false;
			}
		}
		function getAllRow($table,$data=false,$order=false){		
			
			//Возвращает все строки запроса			 
			if(!$data){
				$query="SELECT * FROM ".$this->db_prefix.$table;
				$data1=false;		
			}
			else{
				$query="SELECT * FROM `".$this->db_prefix.$table."` WHERE TRUE ";
				
				$data1=array();	
				foreach(array_keys($data) as $keys){
					$query=$query." AND `".$keys."` = ?";
					array_push($data1,$data[$keys]);
				}								
			}			
			
			if ($order){			
				$query=$query." ORDER BY ".$order['order_field']." ".$order['order_type'];				
			}
			$this->setQuery($query);
			$STH=$this->execute($data1);			
			if ($STH){
				$STH->setFetchMode(PDO::FETCH_ASSOC);
				$result=array();
				while ($row=$STH->fetch()){
					array_push($result,$row);
				}			
				return $result;
			}
			else{				
				return false;
			}
		}
		function insertOneRow($table,$data){
			$keystr="";
			$valuestr="";
			$data1=array();
			foreach(array_keys($data) as $keys){
				$keystr=$keystr.', `'.$keys.'`';	
				array_push($data1,$data[$keys]);
				$valuestr=$valuestr.", ?";
			}		
			$keystr=strstr ( $keystr , ' ');
			$valuestr=strstr ( $valuestr , ' ');
			$this->setQuery("INSERT INTO `".$this->db_prefix.$table."` (".$keystr.") VALUES (".$valuestr.")");
			$STH=$this->execute($data1);
			if ($STH){
				return $this->DBH->lastInsertId();
			}
			else{
				return false;
			}						
		}
		function deleteAllRow($table,$data=false){
			$str="DELETE FROM `".$this->db_prefix.$table."` WHERE TRUE ";
			if ($data){
				$data1=array();
				foreach(array_keys($data) as $keys){
					$str=$str." AND `".$keys."` = ?";
					array_push($data1,$data[$keys]);
				}		
			}
			else{
				$data1=false;
			}
			$this->setQuery($str);			
			$STH=$this->execute($data1);
			if ($STH){				
				return true;
			}
			else{
				return false;
			}						
		}
		function updateRow($table,$field,$value,$data){
			$sql_data=array();
			foreach($data as $key=>$elem){				
				array_push($sql_data,"`".$key."`='".$elem."'");
			}			
			$sql=implode(',',$sql_data);
			$sql="UPDATE `".$this->db_prefix.$table."` SET ".$sql." WHERE `".$field."`=".$value." LIMIT 1";
			$this->setQuery($sql);
			$STH=$this->execute();
			if ($STH){
					return true;
			}
			else{
					return false;
			}		
		}
}		

?>