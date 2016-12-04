<?php 

class HanTuController extends Controller{

	public function init(){

	}
	
	public function indexAction(){
		$this->view->title("Hán Tự");


		$model=$this->load->model("HanTu");

		if($this->request->isPost()){
			if($this->request->post('action')!=null){
				switch ($this->request->post('action')) {

					case 'bat_tat':
						$sl=0;
						foreach ($this->request->post('item') as $item) {
							$arr_split=explode("[[~]]", $item);
							if($model->BatTatKichHoat(array(
								'KICH_HOAT'=>new TCode('CASE WHEN KICH_HOAT=0 THEN 1 ELSE 0 END')
							),(int)$arr_split[0])>0){
								$sl++;
							}
						}
						if($sl>0)
							$this->view->message="Cập nhật thành công ".$sl." hán tự";
						break;
					
					case 'xoa':
						$this->view->message="";
						foreach ($this->request->post('item') as $key=>$value) {
							$arr_split=explode("[[~]]", $value);
							if($model->Xoa((int)$arr_split[0])>0){
								$this->view->message.='<br />Xóa thành công hán tự '.$arr_split[1];
							}else{
								$this->view->message.='<br />Không thể xóa hán tự '.$arr_split[1]." do hán tự này đã được kích hoạt hoặc không tồn tại";
							}
						}
						break;
				}
			}
		}

		

        $orderby="MAHT DESC";

        if($this->request->get('s')!=null){
        	switch ($this->request->get('s')) {
        		case '1':
        			$orderby="NGAY_CAP_NHAT DESC";
        			break;
        		case '2':
        			$orderby="NGAY_CAP_NHAT";
        			break;
        		case '3':
        			$orderby="BO";
        	}
        }

        $query=null;

        if($this->request->get('f')!=null){
        	switch ($this->request->get('f')) {
        		case '1':
        			$query=array("NV_GUI"=>$this->layout->userinfo['MANV']);
        			break;
        		case '2':
        			$query=array("KICH_HOAT"=>1);
        			break;
        		case '3':
        			$query=array("KICH_HOAT"=>0);
        			break;
        	}
        }

        $like=null;

        if($this->request->get('q')!=null){
        	$like=$this->request->get('q');
        	$validate=$this->load->model("Validate");
        	$date=$validate->isDate($like);
        	$query=array();
        	if(isset($date["day"])){
        		$query["day(NGAY_CAP_NHAT)"]=$date["day"];
        	}
        	if(isset($date["month"])){
        		$query["month(NGAY_CAP_NHAT)"]=$date["month"];
        	}
        	if(isset($date["year"])){
        		$query["year(NGAY_CAP_NHAT)"]=$date["year"];
        	}
        	$like2=strtolower($like);
        	if($like2=="n1" || $like2=="n2" || $like2=="n3" || $like2=="n4" || $like2=="n5"){
        		$query["JLPT"]=$like2;
        	}
        	if(count($query)>0){
        		$like=null;
        	}
        }

        $start=0;
        $count=10;


        if($this->request->get('page')!=null){
            $start=$this->request->get('page');
        }
        $paging=$this->load->library('paging');
        $paging->setSumRow((int)$model->Dem($query,$like));
        $paging->setItemInPage($count);
        $paging->showLabel();
        $this->view->setPaging($paging);

		$this->view->data=$model->getData($start*$count,$count,$orderby,$query,$like);

		return $this->view();
	}
	public function themAction(){
		$this->view->title("Thêm hán tự mới");

		if($this->request->isPost()){
			$model=$this->load->model('HanTu');
			$arr=array(
					'MAHT'=>'NULL',
					'TU'=>trim($this->request->post('hantu')),
					'BO'=>trim($this->request->post('bo')),
					'BO_HUAN'=>trim($this->request->post('bohuan')),
					'BO_AM'=>trim($this->request->post('boam')),
					'SONET'=>(int)$this->request->post('sonet'),
					'JLPT'=>$this->request->post('jlpt'),
					'BO_THANH_PHAN'=>trim($this->request->post('bothanhphan')),
					'NGHIA'=>trim($this->request->post('nghia')),
					'GIAI_NGHIA'=>trim($this->request->post('giainghia')),
					'NV_GUI'=>$this->layout->userinfo['MANV'],
					'NGAY_CAP_NHAT'=>new TTimeNow(),
					'KICH_HOAT'=>0
				);

			if($model->KiemTraTuTonTai($this->request->post('hantu'))){
				

				

				if($model->Them($arr)==-1){
					$this->view->message="Có lỗi, thêm thất bại";
					$this->view->data=$arr;
				}else{
					$this->view->message="Thêm thành công";
				}
			}else{
				$this->view->data=$arr;
				$this->view->message="Hán tự ".$this->request->post('hantu')." đã có trong CSDL";
			}
		}

		return $this->view();
	}

	public function suaAction(){
		if($this->request->get('id')==null){
			$this->redirect('han-tu');
		}
		$this->view->title("Sửa Hán Tự");
		$model=$this->load->model("HanTu");

		if($this->request->isPost()){
			$arr=array(
					'TU'=>trim($this->request->post('hantu')),
					'BO'=>trim($this->request->post('bo')),
					'BO_HUAN'=>trim($this->request->post('bohuan')),
					'BO_AM'=>trim($this->request->post('boam')),
					'SONET'=>(int)$this->request->post('sonet'),
					'JLPT'=>$this->request->post('jlpt'),
					'BO_THANH_PHAN'=>trim($this->request->post('bothanhphan')),
					'NGHIA'=>trim($this->request->post('nghia')),
					'GIAI_NGHIA'=>trim($this->request->post('giainghia')),
					'NGAY_CAP_NHAT'=>new TTimeNow(),
				);
			if($model->Sua($arr,$this->request->post('ma'))==0){
				$this->view->message="Sửa thất bại, có thể hán tự đã tồn tại";
			}else{
				$this->view->message="Sửa thành công";
			}
		}

		$result=$model->getInfo($this->request->get('id')); 
		if(count($result)==1)
			$this->redirect('han-tu');
		if($result['NV_GUI']!=$this->layout->userinfo['MANV'] && $this->layout->userinfo['LAADMIN']==0){
			$this->view->setView("error/e");
			$this->view->ten=$result['TU'];
		}else{
			$this->view->data=$result;
		}

		return $this->view();
	}

}