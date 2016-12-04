<?php 

class NojavascriptController extends Controller{
	public function init(){

	}
	public function indexAction(){
		$this->layout->disable();
		return $this->view();
	}
}