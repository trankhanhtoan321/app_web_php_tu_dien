<?php 

class TException{
	private $message;
	private $error_number;
	private $title;
	public function __construct($message='Không tìm thấy trang',$error_number=404,$title="Có lỗi trong quá trình sử lý"){
		$this->message=$message;
		$this->error_number=$error_number;
		$this->title=$title;

		$path=APPLICATION_PATH.'view/error/'.$error_number.EX_VIEW_FILE;
	

		if(file_exists($path)){
			require_once $path;
		}else{
			header("Content-Type:text/html;charset=utf-8");
			echo $message;
		}
		exit("");
	}

	private function getMessage(){
		return $this->message;
	}
	private function getErrorNumber(){
		return $this->error_number;
	}
	private function getTitle(){
		return $this->title;
	} 
}