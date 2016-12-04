<?php 

class IndexController extends Controller{

	public function init(){
		
	}
	
	public function indexAction(){
		$this->view->title("Admin Control Panel");
		$model=$this->load->model('Index');
		if($model->getMatKhau($this->layout->userinfo['MANV'])==md5('123456')){
			$this->view->updatepass=true;
		}
		$dem=$model->Dem();

		$this->view->sltv=$dem['sltv'];
		$this->view->slht=$dem['slht'];
		$this->view->slnp=$dem['slnp'];
		$this->view->slnv=$dem['slnv'];
		$this->view->slchuacoaudio=$model->SLTuVungChuaCoAudio();
		$this->view->slchuacovd=$model->SLNPChuaCoVD();
		return $this->view();
	}


}