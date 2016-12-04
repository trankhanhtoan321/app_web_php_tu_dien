<?php 

require_once SYSTEM_PATH."db/TDatabase.php";

class Table extends TDatabase{
	
	protected function delete(array $where=null,$tb=null){
		if($where==null && $this->_where==null)
			return -1;
		
		if($this->table==null && $tb==null && $this->_table==null)
			return -1;

		try {


			if($tb!=null)
				$this->from($tb);

			if($where!=null){
				$this->where($where);
			}

			$tb=$this->table;
			if($this->_table!=null)
				$tb=$this->_table;

			$this->_query="delete from ".$tb;

			if($this->_where!=""){
				$this->_query.=" where ".$this->_where;
				$this->_where="";
			}

		

			mysql_query($this->_query);
			$this->_query=null;
			return mysql_affected_rows();
		} catch (Exception $e) {
			return -1;
		}
	}

	protected function update(array $arr,array $where=null,$tb=null){
		
		if($this->table==null && $tb==null && $this->_table==null)
			return -1;

		if(count($arr)==0)
			return -1;

		$this->_query="update ";
		
		if($tb!=null){
			$this->_query.=$tb;
		}else{
			if($this->_table!=null){
				$this->_query.=$this->_table;
				$this->_table=null;
			}
			else
				$this->_query.=$this->table;
		}

		$this->_query.=" set ";

		foreach ($arr as $key => $value) {
			$this->gett($value,$key);
		}

		$this->_query=substr($this->_query, 0,strlen($this->_query)-1);

		if($where!=null){
			$this->where($where);
		}

		if($this->_where!=""){
			$this->_query.=" where ".$this->_where;
			$this->_where="";
		}


		mysql_query($this->_query);
		$this->_query=null;
		return mysql_affected_rows();

	}

	protected function insert(array $arr,$tb=null){
		
		if($this->table==null && $tb==null && $this->_table==null)
			return -1;

		if(count($arr)==0)
			return -1;

		$this->_query="insert into ";
		
		if($tb!=null){
			$this->_query.=$tb;
		}else{
			if($this->_table!=null){
				$this->_query.=$this->_table;
				$this->_table=null;
			}
			else
				$this->_query.=$this->table;
		}

		$this->_query.=" values(";

		foreach ($arr as $key => $value) {
			if(!isset($value))
				$this->_query.="NULL,";
			else{
				$this->gett($value);
			}
		}

		$this->_query=substr($this->_query, 0,strlen($this->_query)-1);
		$this->_query.=")";




		mysql_query($this->_query);
		$this->_query=null;
		return mysql_insert_id();

	}
}