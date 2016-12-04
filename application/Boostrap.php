<?php 

class Boostrap extends TBoostrap{
	public function init(){
		if($this->getControllerName()!="NojavascriptController"){
			$s=$this->load->library("session");
			if($this->getControllerName()=="LoginController"){
				if($s->login!=null){
					$this->redirect("");
				}
			}else{
				if($this->getControllerName()!="LogoutController"){
					if($s->login==null){
						$this->redirect("login?redirect=".$this->getCurrentPath());
					}else{
						$this->layout->userinfo=$s->login;
					
					}
				}
			}
		}
	}
}