<?php 

class UserController extends Controller{
	public function init(){

	}
	public function indexAction(){
		$this->view->title('Thông tin cá nhân');
		$model=$this->load->model('User');
		if($this->request->isPost()){
			
			if($this->request->post('changepass')!=null){
				$pass_cu=$model->getPass($this->layout->userinfo['MANV']);
				if($pass_cu!=md5($this->request->post('passwordcu'))){
					$this->view->message="Mật khẩu cũ sai";
				}else{
					if($model->changePass($this->layout->userinfo['MANV'],$this->request->post('passwordmoi'))>0){
						$this->view->message="Cập nhật mật khẩu thành công";
					}else{
						$this->view->message="Cập nhật mật khẩu thất bại";
					}
				}
			}else{
				if($this->request->post('changeprofile')){
					$result=$model->ChangeProfile(array(
						'HOTEN'=>trim($this->request->post('hoten')),
						'GIOITINH'=>(int)$this->request->post('gt'),
						'DIENTHOAI'=>trim($this->request->post('dienthoai')),
						'DIACHI'=>trim($this->request->post('diachi'))
					),$this->layout->userinfo['MANV']);
					if($result>0){
						$this->view->message2="Cập nhật thông tin thành công";
					}else{
						$this->view->message2="Cập nhật thông tin thất bại";
					}
				}else{
					if($_FILES['avatar']['size']>0){
						if(move_uploaded_file($_FILES['avatar']['tmp_name'],$this->getRootPath()."public/images/avatar/".$this->layout->userinfo['MANV'].".png")){
							$this->view->message3="Cập nhật avatar thành công";
						}else{
							$this->view->message3="Cập nhật avatar thất bại";
						}
					}
				}
			}
		}

		$this->view->data=$model->getInfo($this->layout->userinfo['MANV']);

		return $this->view();
	}
}