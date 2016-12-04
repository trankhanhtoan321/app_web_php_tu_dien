<?php

class Query extends ConnectDb{

	protected function Select($q){
		$result=mysql_query($q);

		if($result){

			$arr=array();


			while ($row=mysql_fetch_row($result)) {
				$arr[]=$row;
			}
			return $arr;
		}
		return null;
	}

	protected function Insert($q){
		mysql_query($q);

		return mysql_insert_id();
	}

	protected function Update($q){
		mysql_query($q);

		return mysql_affected_rows();
	}

	protected function Delete($q){
		mysql_query($q);

		return mysql_affected_rows();
	}

	protected function Row($q){
		$result=mysql_query($q);


		while ($row=mysql_fetch_array($result)) {
			return $row;
		}
		return null;
	}
}