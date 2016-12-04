<?php 

class LogoutController extends Controller{

	public function init(){
		
	}
	
	public function indexAction(){
		$session=$this->load->library("session");
		unset($session->login);
		$this->redirect("login");
	}


}