<?php 
require_once SYSTEM_PATH."db/Database.php";
require_once SYSTEM_PATH."db/Query.php";
require_once SYSTEM_PATH."db/Table.php";
require_once SYSTEM_PATH."db/Select.php";
require_once SYSTEM_PATH."db/TMd5.php";
require_once SYSTEM_PATH."db/TTimeNow.php";
require_once SYSTEM_PATH."db/TCode.php";

class Model{
	private $db=null;
	private $cmodel=array();
	public function __construct($db){
		if($db!=null){
			$this->db=$db;
		}
	}

	public function load($model,$options=null){
		if(file_exists(APPLICATION_PATH."model/".$model.".php")){
			require_once APPLICATION_PATH."model/".$model.".php";
			$cmodel=null;
			if($options==null)
				$cmodel=new $model();
			else
				$cmodel=new $model($options);
			if($this->db!=null){
				if(method_exists($cmodel, "connect")){
						$cmodel->connect($this->db);
				}
			}

			return $cmodel;
		}
		return new TException("Không tìm thấy model ".$model,401);
	}
}