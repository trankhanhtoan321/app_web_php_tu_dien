<?php 

class LoginController extends Controller{

	public function init(){
		$session=$this->load->library("session");
		if($session->login!=null){
			$this->redirect("");
		}
		$this->layout->set("login/index");
	}
	
	public function indexAction(){
		$this->view->title("Đăng Nhập ACP");
		

		if($this->request->isPost()){
			$session=$this->load->library("session");
			
			$model=$this->load->model("Login");
			$user=$model->check($this->request->post('username'),$this->request->post('password'));
			if(!empty($user)){
				if($user['KHOA']==0){
					$session->login=$user;
					$this->redirect($this->request->get('redirect'));
				}else{
					$this->view->message="Tài khoản của bạn đã bị khóa.";
				}
			}else{
				$this->view->message="Đăng nhập thất bại.";
			}

		}

		$paging=$this->load->library("paging");
		$paging->setSumRow(100);
		$paging->setItemInPage(5);
		$paging->showLabel();
		
		$this->view->setPaging($paging);


		return $this->view();
	}

	public function quyenmkAction(){
		$this->view->title("Lấy lại mật khẩu");
		
		if($this->request->isPost()){
			$model=$this->load->model("Login");
			if($model->checkMail($this->request->post("email"))){
				$str="qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
		        $pass="";
		        for($i=0;$i<10;$i++){
				  $pass.=substr($str, rand(0,61),1);
		        }
				$msg = "<html><body>Mật khẩu của bạn là: ".$pass."</body></html>";

				$msg = wordwrap($msg,70);

				$headers = "From: dntudict@gmail.com\r\nContent-type:text/html;charset=UTF-8" . "\r\n";
				
					$result=@mail($this->request->post('email'),"Lấy lại mật khẩu",$msg,$headers) or new TException("Không thể send mail.",401);
					if($result){
						
						$model->updatePass($this->request->post('email'),$pass);
						$this->view->message="Mật khẩu của bạn đã được gửi đến email ".$this->request->post('email');
					}

			}else{
				$this->view->message="Email không tồn tại.";
			}
		}
		return $this->view();
	}


}