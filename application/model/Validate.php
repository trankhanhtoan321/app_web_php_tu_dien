<?php 

class Validate{
	public function isDate($day){
    	$arrday=explode("/", $day);
    	$dat=array();


    	switch (count($arrday)) {
    		case 1:
    			if((int)$arrday[0]!=0)
    				$dat["year"]=$arrday[0];
    			break;
    		case 2:
    			if((int)$arrday[0]!=0 && (int)$arrday[1]!=0){
    				$dat["month"]=$arrday[0];
    				$dat["year"]=$arrday[1];
    			}
    			break;
    		case 3:
    			if((int)$arrday[0]!=0 && (int)$arrday[1]!=0 && (int)$arrday[2]!=0){
    				$dat["day"]=$arrday[0];
    				$dat["month"]=$arrday[1];
    				$dat["year"]=$arrday[2];
    			}
    			break;
    		default:
    			# code...
    			break;
    	}

    	if(count($dat)>0){
    		return $dat;
    	}
    	return false;
    }
}