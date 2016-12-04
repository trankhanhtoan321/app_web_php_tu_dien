<?php 

class NhanVienController extends Controller{
	public function init(){
		if($this->layout->userinfo['LAADMIN']==0)
			$this->redirect("");
	}

	public function indexAction(){
		$this->view->title("Nhân Viên");
		$model=$this->load->model('NhanVien');

		if($this->request->isPost()){
			$this->view->message="";
			foreach ($this->request->post('item') as $item) {
				$arr_split=explode("~", $item);
				if($model->Xoa((int)$arr_split[0])>0){
					$this->view->message.="Xóa thành công NV ".$arr_split[1]."<br />";
				}else{
					$this->view->message.="Không thể xóa NV ".$arr_split[1]." Có thể NV này đã gửi Hán Tự, Ngữ Pháp hoặc Từ Vựng"."<br />";
				}
			}			
		}


		if($this->request->get('id')!=null){
			$result=$model->resetPass($this->request->get('id'));
			if($result>0){
				$this->view->message="Cập nhật thành công mật khẩu";
			}else{
				$this->view->message="Có lỗi, cập nhật thất bại";
			}
		}

		if($this->request->get('idk')!=null){
			$model->Khoa($this->request->get('idk'));
			$this->redirect('nhan-vien');
		}


		$like=null;

        if($this->request->get('q')!=null){
        	$like=$this->request->get('q');
        }

		$this->view->data=$model->getNV($this->layout->userinfo['MANV'],$like);
		return $this->view();
	}
	public function themAction(){
		$this->view->title("Thêm Nhân Viên");
		if($this->request->isPost()){
			$model=$this->load->model('NhanVien');
			if(!$model->checkEmail($this->request->post('EMAIL'))){
				$result=$model->Them($this->post_r(array('AVATAR'),'MANV'));

				if($result>0){
					$this->view->message="Thêm thành công";

					if($_FILES['AVATAR']['size']>0){
						move_uploaded_file($_FILES['AVATAR']['tmp_name'],$this->getRootPath()."public/images/avatar/".$result.".png");
					}
				}else{
					$this->view->message="Có lỗi, Thêm thất bại";
					$this->view->data=$this->post_r(array('AVATAR'));
				}
			}else{
				$this->view->message="Email ".$this->request->post('EMAIL')." đã có người sử dụng. Vui lòng điền email khác";
				$this->view->data=$this->post_r(array('AVATAR'));
			}
		}
		return $this->view();
	}
	public function suaAction(){
		if($this->request->get('id')==null){
			$this->redirect('nhan-vien');
		}
		$this->view->title("Sửa Nhân Viên");
		$model=$this->load->model("NhanVien");

		if($this->request->isPost()){
			$result=$model->Sua($this->post_r(array('AVATAR','ma')),$this->request->post('ma'));
			if($result>=0){
				$this->view->message="Sửa thành công";
				if($_FILES['AVATAR']['size']>0){
						move_uploaded_file($_FILES['AVATAR']['tmp_name'],$this->getRootPath()."public/images/avatar/".$this->request->post('ma').".png");
					}
			}else{
				$this->view->message="Sửa thất bại";
			}
		}

		$result=$model->getInfo($this->request->get('id')); 
		if(count($result)==1)
			$this->redirect('nhan-vien');

		$this->view->data=$result;

		return $this->view();
	}
}