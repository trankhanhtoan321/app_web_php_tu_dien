<?php 

class ConnectDb{

	protected $conn=null;
	protected $driver;

	public function __construct($db=null){
		if($db!=null)
			$this->connect($db);
	}

	public function connect($db){
		$this->driver=$db["driver"];
		switch ($this->driver) {
			case 'mysql':
				$this->conn=@mysql_connect($db['host'],$db['username'],$db['password']);
				if(!$this->conn){
					return new TException("Error Connection Mysql: ".mysql_error(),401,"Lỗi kết nối csdl");
				}
				mysql_select_db($db['dbname'],$this->conn) or new TException("<b>Error Connection Mysql</b>: ".mysql_error(),401,"Lỗi kết nối csdl");
				if(isset($db['time_zone'])){
					mysql_query("SET time_zone='".$db['time_zone']."'") or new TException("<b>Error Connection Mysql</b>: ".mysql_error(),401,"Lỗi kết nối csdl");
				}
				mysql_query("set names '".$db['charset']."'") or new TException("<b>Error Connection Mysql</b>: ".mysql_error(),401,"Lỗi kết nối csdl");


				break;
			case 'mongo':
				break;
			default:
				return new TException("driver ".$this->driver." không tồn tại. Chỉ hỗ trợ mysql, mongodb",401);
				break;
		}
	}

	public function __destruct(){
		if($this->conn!=null)
			mysql_close($this->conn);
		$this->conn=null;
	}

}