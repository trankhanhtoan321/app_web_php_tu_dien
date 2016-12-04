<?php 

class NguPhapController extends Controller{

	public function init(){

	}
	
	public function indexAction(){
		$this->view->title("Ngữ Pháp");
		$model=$this->load->model("NguPhap");

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
							$this->view->message="Cập nhật thành công ".$sl." ngữ pháp";
						break;
					
					case 'xoa':
						$this->view->message="";
						foreach ($this->request->post('item') as $value) {
							$arr_split=explode("[[~]]", $value);
							if($model->Xoa((int)$arr_split[0])>0){
								$this->view->message.='<br />Xóa thành công ngữ pháp '.$arr_split[1];	
							}else{
								$this->view->message.='<br />Không thể xóa ngữ pháp '.$arr_split[1]." do ngữ pháp này đã được kích hoạt hoặc không tồn tại hoặc có ví dụ";
							}
						}
						break;
				}
			}
		}

		$orderby="MANP DESC";

        if($this->request->get('s')!=null){
        	switch ($this->request->get('s')) {
        		case '1':
        			$orderby="NGAY_CAP_NHAT DESC";
        			break;
        		case '2':
        			$orderby="NGAY_CAP_NHAT";
        			break;
        		case '3':
        			$orderby="Y_NGHIA";
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
        		case '4':
        			$query=array(new TCode('(select count(MAVD) from nguphap_vd where MANP=ngu_phap.MANP)=0'));
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

		$in="";
        foreach ($this->view->data as $item) {
            $in.=$item["MANP"].",";
        }
        $in=mb_substr($in, 0,strlen($in)-1);
        $this->view->vd=$model->getVD($in);
		return $this->view();
	}

	public function themAction(){
		$this->view->title("Thêm Ngữ Pháp");

		if($this->request->isPost()){
			$model=$this->load->model("NguPhap");

			$arr=array(
				'MANP'=>'NULL',
				'TIENG_NHAT'=>trim($this->request->post('nguphap')),
				'Y_NGHIA'=>trim($this->request->post('ynghia')),
				'CAU_TRUC'=>trim($this->request->post('cautruc')),
				'MIEU_TA'=>trim($this->request->post('mieuta')),
				'CHU_Y'=>trim($this->request->post('chuy')),
				'JLPT'=>$this->request->post('jlpt'),
				'NV_GUI'=>$this->layout->userinfo['MANV'],
				'NGAY_CAP_NHAT'=>new TTimeNow(),
				'KICH_HOAT'=>0
			);

			if($model->KiemTra($arr['TIENG_NHAT'])){
				$result=$model->Them($arr);
				if($result!=-1){
					if($this->request->post('vd_nhat')!=null){
						$arr_viet=$this->request->post('vd_viet');
						foreach ($this->request->post('vd_nhat') as $key => $value) {
							if(trim($value)!='' && trim($arr_viet[$key])!=''){
								$model->ThemVD(array(
									'MAVD'=>'NULL',
									'MANP'=>$result,
									'NHAT'=>trim($value),
									'VIET'=>trim($arr_viet[$key])
								));
							} 
						}
					}
					$this->view->message="Thêm thành công";
				}else{
					$this->view->message="Thêm thất bại";
					$this->view->data=$arr;
				}
			}else{
				$this->view->message="Ngữ pháp ".$arr['TIENG_NHAT']." đã có trong CSDL";
				$this->view->data=$arr;
			}
		}

		return $this->view();
	}
	public function suaAction(){
		if($this->request->get('id')==null){
			$this->redirect('ngu-phap');
		}
		$this->view->title("Sửa Ngữ Pháp");
		$model=$this->load->model("NguPhap");

		if($this->request->isPost()){
			$arr=array(
				'TIENG_NHAT'=>trim($this->request->post('nguphap')),
				'Y_NGHIA'=>trim($this->request->post('ynghia')),
				'CAU_TRUC'=>trim($this->request->post('cautruc')),
				'MIEU_TA'=>trim($this->request->post('mieuta')),
				'CHU_Y'=>trim($this->request->post('chuy')),
				'JLPT'=>$this->request->post('jlpt'),
				'NGAY_CAP_NHAT'=>new TTimeNow(),
			);
			if($model->Sua($arr,$this->request->post('ma'))==0){
				$this->view->message="Sửa thất bại, có thể ngữ pháp đã tồn tại";
			}else{
				$this->view->message="Sửa thành công";
			}
		}

		$result=$model->getInfo($this->request->get('id')); 
		if(count($result)==1)
			$this->redirect('ngu-phap');
		if($result['NV_GUI']!=$this->layout->userinfo['MANV'] && $this->layout->userinfo['LAADMIN']==0){
			$this->view->setView("error/e");
			$this->view->ten=$result['TIENG_NHAT'];
		}else{
			$this->view->data=$result;
		}

		return $this->view();
	}
}