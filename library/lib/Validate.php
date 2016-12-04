<?php 

class Validate{
	public function isPhoneNumber($phone_number){
    	return preg_match("/^0([0-9]{2,3})(\.|-| )?([0-9]{3,4})(\.|-| )?([0-9]{3,4})$/", $phone_number);
    }

    public function isEmail($email){
    	return preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email);
    }

    public function isCharacter($str){
    	return preg_match("/^([a-zA-Z\\u00A1-\\uFFFF ])+$/", $str);
    }

    public function isPrice($price){
    	return preg_match("/^[0-9]{1,3}( |-|\.|\,)?[0-9]{3}(( |-|\.|\,)?[0-9]{3})?(( |-|\.|\,)?[0-9]{3})?(( |-|\.|\,)?[0-9]{3})?$/", $price);
    }

    public function isNumber($number){
    	return preg_match("/^[0-9]+$/", $number);
    }

    public function isImage($file) {
	    $duoi=end(explode(".", $file));

	    switch ($duoi) {
	        case "jpg": case "png": case "gif": case "bimap": case "jpeg": case "ico": case "bmp":
	        case "jpe":
	            return true;
	        default:
	            return false;
	    }
	    return false;
	}

	public function isVideo($file) {
	    $duoi=end(explode(".", $file));

	    switch ($duoi) {
	        case "mp4": case "avi": case "flv": case "3gp":
	            return true;
	        default:
	            return false;
	    }
	    return false;
	}

	function isDate($date){
		$date=explode("/", $date);

		if(count($date)==3){
			$ngay=(int)$date[0];
			$thang=(int)$date[1];
			$nam=(int)$date[2];

			if($thang>0 && $thang<13){
				switch($thang){
					case 1: case 3: case 5: case 7: case 8: case 10: case 12:
						return $ngay<32 && $ngay>0;
					case 2:
						if(($nam%4==0 && $nam%100!=0) || $nam%400==0)
							return $ngay<30 && $ngay>0;
						return $ngay<29 && $ngay>0;
					default:
						return $ngay<31 && $ngay>0;
				}
			}
			return false;
		}

		return false;
	}
}