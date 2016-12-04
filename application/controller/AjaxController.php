<?php 

class AjaxController extends Controller{
	public function init(){

	}
	public function them_vd_npAction(){
		if($this->request->isPost()){
			$model=$this->load->model("NguPhap");
			$result=$model->ThemVD(array(
				'MAVD'=>'NULL',
				'MANP'=>(int)$this->request->post('MANP'),
				'NHAT'=>trim($this->request->post('NHAT')),
				'VIET'=>trim($this->request->post('VIET'))
			));
			if($result!=-1){
				$model->Sua(array("NGAY_CAP_NHAT"=>new TTimeNow()),(int)$this->request->post('MANP'));
			}
			return $this->json(array("success"=>$result));
		}
	}
	public function xoa_vd_npAction(){
		if($this->request->isPost()){
			$model=$this->load->model("NguPhap");
			$result=$model->XoaVD($this->request->post("id"));
			return $this->json(array("success"=>$result));	
		}
	}
	public function sua_vd_npAction(){
		if($this->request->isPost()){
			$model=$this->load->model("NguPhap");
			$result=$model->SuaVD(array(
				"NHAT"=>$this->request->post('NHAT'),
				"VIET"=>$this->request->post('VIET')
			),$this->request->post("MAVD"));
		
			return $this->json(array("success"=>$result));	
		}
	}
	public function them_nghia_tvAction(){
		if($this->request->isPost()){
			$model=$this->load->model("TuVung");
			$result=$model->ThemNghia(array(
				'MANGHIA'=>'NULL',
				'MATU'=>(int)$this->request->post('MANP'),
				'NGHIA_TIENGVIET'=>trim($this->request->post('VIET')),
				'NGHIA_TIENGNHAT'=>trim($this->request->post('NHAT'))
			));
			if($result!=-1){
				$model->Sua(array("NGAY_CAP_NHAT"=>new TTimeNow()),(int)$this->request->post('MANP'));
			}
			return $this->json(array("success"=>$result));
		}
	}
	public function xoa_nghia_tvAction(){
		if($this->request->isPost()){
			$model=$this->load->model("TuVung");
			$result=$model->XoaNghia($this->request->post("id"));
			return $this->json(array("success"=>$result));	
		}
	}
	public function sua_nghia_tvAction(){
		if($this->request->isPost()){
			$model=$this->load->model("TuVung");
			$result=$model->SuaNghia(array(
				"NGHIA_TIENGNHAT"=>$this->request->post('NHAT'),
				"NGHIA_TIENGVIET"=>$this->request->post('VIET')
			),$this->request->post("MAVD"));
		
			return $this->json(array("success"=>$result));	
		}
	}

	public function sua_vd_nghia_tvAction(){
		if($this->request->isPost()){
			$model=$this->load->model("TuVung");
			$result=$model->SuaVDNghia(array(
				"VIDU_TV"=>$this->request->post('VIET'),
				"VIDU_TN"=>$this->request->post('NHAT')
			),$this->request->post("MAVD"));
		
			return $this->json(array("success"=>$result));	
		}
	}
	public function them_vd_nghia_tvAction(){
		if($this->request->isPost()){
			$model=$this->load->model("TuVung");
			$result=$model->ThemVDNghia(array(
				'MA'=>'NULL',
				'MANGHIA'=>(int)$this->request->post('MANGHIA'),
				'VIDU_TV'=>trim($this->request->post('VIET')),
				'VIDU_TN'=>trim($this->request->post('NHAT'))
			));
			return $this->json(array("success"=>$result));
		}
	}
	public function xoa_vd_nghia_tvAction(){
		if($this->request->isPost()){
			$model=$this->load->model("TuVung");
			$result=$model->XoaVDNghia($this->request->post("id"));
			return $this->json(array("success"=>$result));	
		}
	}
}