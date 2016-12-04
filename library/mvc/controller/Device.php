<?php 

class Device{
	public function isMobile(){
		return preg_match("/android|iphone|ipad|blackberry|nokia|opera mini|windows mobile|windows phone|iemobile/", $_SERVER['HTTP_USER_AGENT']);
	}
}