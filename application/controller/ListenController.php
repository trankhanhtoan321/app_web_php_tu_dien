<?php 

class ListenController extends Controller{

	public function init(){

	}
	
	public function audioAction(){
		header("Content-Disposition:attachment;filename='".($this->view->baseUrl()."upload/audio/".$this->request->get('id').".mp3")."'");
	}

	

}