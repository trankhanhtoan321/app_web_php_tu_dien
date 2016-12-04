<?php 

require_once SYSTEM_PATH."db/ConnectDb.php";

class TDatabase extends ConnectDb{
	
	protected $table=null;


	protected $_table=null;
	protected $_columns=null;
	protected $_from=null;
	protected $_where="";
	protected $_order=null;
	protected $_limit=null;
	protected $_join="";
	protected $_groupBy=null;
	protected $_having=null;
	protected $_query=null;
	

	protected function getQuery(){
		return $this->_query;
	}
	

	protected function select($columns=null){
		if($columns!=null){
			$this->_columns=mysql_real_escape_string($columns);
		}else{
			$this->_columns="*";
		}
		return $this;
	}

	protected function from($from=null){
		if($from!=null){
			$this->_table=$from;
		}
		return $this;
	}

	protected function where(array $where=null,$r='AND'){
		return $this->tWhere($where,$r);
	}

	protected function not_where(array $where=null,$r='AND'){
		return $this->tWhere($where,$r,'<>');
	}

	private function tWhere(array $where=null,$r='AND',$k='='){
		if($where!=null){

			$r=trim($r);

			$w="";

			foreach ($where as $key => $value) {
				if(gettype($value)=="string")
					$w.=sprintf("%s%s'%s' %s ",mysql_real_escape_string($key),$k,mysql_real_escape_string($value),$r);
				else{
					if($value instanceof TMd5){
						$w.=sprintf("%s%smd5(%s) %s ",mysql_real_escape_string($key),$k,mysql_real_escape_string($value->value),$r);
					}else{
						if($value instanceof TTimeNow){
							$w.=sprintf("%s%snow() %s ",mysql_real_escape_string($key),$k,$r);
						}else{
							if($value instanceof TCode){
								if($key=="" || is_integer($key))
									$w.=sprintf("%s %s ",mysql_real_escape_string($value->get()),$r);
								else
									$w.=sprintf("%s%s%s %s ",mysql_real_escape_string($key),$k,mysql_real_escape_string($value->get()),$r);
							}else
							$w.=sprintf("%s%s%s %s ",mysql_real_escape_string($key),$k,mysql_real_escape_string($value),$r);
						}
					}
					
				}
			}
			$w=substr($w, 0,strlen($w)-strlen($r)-2);

			if($this->_where==""){
				$this->_where=$w;
			}else{
				$this->_where.=" ".$w;
			}
		}
		
		return $this;
	}

	protected function in($column=null,$value=null,$r='AND'){
		if(!empty($column) && !empty($value)){
			if($this->_where==""){
				$this->_where=$column." in(".$value.")";
			}else{
				$this->_where.=" ".$r." ".$column." in(".$value.")";
			}
		}
		return $this;
	}

	protected function not_in($column=null,$value=null,$r='AND'){
		if(!empty($column) && !empty($value)){
			if($this->_where==""){
				$this->_where=$column." not in(".$value.")";
			}else{
				$this->_where.=" ".$r." ".$column." not in(".$value.")";
			}
		}
		return $this;
	}

	protected function orderBy($order=null){
		if($order!=null){
			$order=explode(";", $order);
			$this->_order=mysql_real_escape_string($order[0]);
		}
		return $this;
	}

	protected function limit($start=null,$count=null){
		
		if($start==null && $count==null)
			return $this;

		if($start!=null){
			$this->_limit=(mysql_real_escape_string((string)$start)).','.(mysql_real_escape_string((string)$count));	
		}else{
			$this->_limit='0,'.mysql_real_escape_string((string)$count);
		}
		return $this;
	}

	protected function join($tb=null,$on=null){
		
		if($tb==null || $on==null)
			return $this;

		$this->_join.=' inner join '.$tb.' on '.$on;
		
		return $this;
	}

	protected function leftJoin($tb=null,$on=null){
		
		if($tb==null || $on==null)
			return $this;

		$this->_join.=' left outer join '.$tb.' on '.$on;
		
		return $this;
	}

	protected function rightJoin($tb=null,$on=null){
		
		if($tb==null || $on==null)
			return $this;

		$this->_join.=' right outer join '.$tb.' on '.$on;
		
		return $this;
	}


	protected function groupBy($g=null){
		if($g!=null){
			$this->_groupBy=mysql_real_escape_string($g);
		}
		return $this;
	}

	protected function having($h=null){
		if($h!=null){
			$this->_having=mysql_real_escape_string($h);
		}
		return $this;
	}

	protected function like($cl,$vl,$l=null){
		if(!isset($cl) || !isset($vl))
			return $this;
		
		if($this->_where==""){
			$this->_where=$cl." like '%".mysql_real_escape_string($vl)."%'";
		}else{
			if(isset($l)){
				$this->_where.=" ".$l." ".$cl." like '%".mysql_real_escape_string($vl)."%'";
			}else{
				$this->_where.=" and ".$cl." like '%".mysql_real_escape_string($vl)."%'";
			}
		}
		return $this;
	}

	protected function fetchAll(array $where=null,$count=null){
		
		if($where!=null){
			$this->where($where);
		}

		if($count!=null){
			$this->limit(null,$count);
		}

		return $this->toArray();
	}

	protected function toArray(){
		
		$this->CreateQuery();

		$result=mysql_query($this->_query);
		$this->_query=null;

		if($result){


			$arr=array();

			while($row=mysql_fetch_assoc($result)){
				$arr[] = $row;
			}

			return $arr;
		}
		return null;
	}

	protected function row(){
		$this->CreateQuery();

		$result=mysql_query($this->_query);
		$this->_query=null;

		if($result){
			$row=mysql_fetch_assoc($result);
			return $row;
		}
		return null;
	}

	protected function row_array(){
		$this->CreateQuery();

		$result=mysql_query($this->_query);

		$this->_query=null;

		if($result){
			$row=mysql_fetch_array($result);
			return $row;
		}
		return null;
	}

	protected function column($column=0){
		$row=$this->row_array();
		if($row!=null){
			return $row[$column];
		}
		return null;
	}

	protected function f(){
		$result=mysql_query($this->_query);
		$this->_query=null;

		$count=0;
		if($result){
			$count=mysql_fetch_assoc($result);
			return $count['c'];
		}
		return -1;
	}

	protected function exists($cl=null){
		$sl=$this->count($cl);
		return ($sl==0 || $sl==-1)?false:true;
	}

	protected function count($cl=null){
		
		if($cl==null)
			$cl="*";

		$this->CreateQuery('count',$cl);

		return $this->f();
	}


	protected function sum($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('sum',$cl);

		return $this->f();
	}

	protected function avg($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('avg',$cl);

		return $this->f();
	}

	protected function max($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('max',$cl);

		return $this->f();
	}

	protected function min($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('min',$cl);

		return $this->f();
	}



	protected function CreateQuery($v=null,$v2=null){
		if($this->_query==null){
			if($this->_columns==null)
				$this->_columns="*";
			$tb=$this->table;
			if($this->_table!=null)
				$tb=$this->_table;
			
			if($v==null)
				$this->_query="select ".$this->_columns;
			else
				$this->_query="select ".$v."(".$v2.") as c";

			$this->_query.=" from ".$tb;

			$this->_columns=null;
			$this->_table=null;

			if($this->_join!=""){
				$this->_query.=$this->_join;
				$this->_join="";
			}

			if($this->_where!=""){
				$this->_query.=" where ".$this->_where;
				$this->_where="";
			}

			if($this->_groupBy!=null){
				$this->_query.=" group by ".$this->_groupBy;
				$this->_groupBy=null;
			}

			if($this->_having!=null){
				$this->_query.=" having ".$this->_having;
				$this->_having=null;
			}

			if($this->_order!=null){
				$this->_query.=" order by ".$this->_order;
				$this->_order=null;
			}

			if($this->_limit!=null){
				$this->_query.=" limit ".$this->_limit;
				$this->_limit=null;
			}
		}
	}

	protected function gett($value,$key=null){
		switch (gettype($value)) {
			case 'string':
				if($key==null)
					$this->_query.="'".$value."',";
				else
					$this->_query.=$key."='".$value."',";
				break;
			case 'boolean':
				if($key==null)
					$this->_query.="b'".$value."',";
				else
					$this->_query.=$key."=b'".$value."',";
				break;
			case 'integer':
				if($key==null)
					$this->_query.=$value.",";
				else
					$this->_query.=$key."=".$value.",";
				break;
			default:
				if($value instanceof TMd5){
					if($key==null)
						$this->_query.="md5('".$value->value."'),";
					else
						$this->_query.=$key."=md5('".$value->value."'),";
				}else{
					if($value instanceof TTimeNow){
						if($key==null)
							$this->_query.="now(),";
						else
							$this->_query.=$key."=now(),";
					}else{
						if($value instanceof TCode){
							if($key==null)
								$this->_query.=$value->get().",";
							else
								$this->_query.=$key."=".$value->get().",";
						}
					}
				}
				break;
		}
	}

}