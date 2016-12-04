<?php 

class TuVungController extends Controller{

	public function init(){

	}
	
	public function indexAction(){
		$this->view->title("Từ Vựng");

		$model=$this->load->model("TuVung");

		if($this->request->isPost()){
			if($this->request->post('action')!=null){
				switch ($this->request->post('action')) {

					case 'bat_tat':
						$sl=0;
						foreach ($this->request->post('item') as $item) {
							$arr_split=explode("~", $item);
							if($model->BatTatKichHoat(array(
								'KICH_HOAT'=>new TCode('CASE WHEN KICH_HOAT=0 THEN 1 ELSE 0 END')
							),(int)$arr_split[0])>0){
								$sl++;
							}
						}
						if($sl>0)
							$this->view->message="Cập nhật thành công ".$sl." từ vựng";
						break;
					
					case 'xoa':
						$this->view->message="";
						foreach ($this->request->post('item') as $value) {
							$arr_split=explode("~", $value);
							if($model->Xoa((int)$arr_split[0])>0){
								if($arr_split[2]!=''){
									$filename=explode("/", $arr_split[2]);
									$filename=trim($filename[count($filename)-1]);
									unlink($this->getRootPath().'upload/audio/'.$filename);
								}
								$this->view->message.='<br />Xóa thành công từ vựng '.$arr_split[1];
							}else{
								$this->view->message.='<br />Không thể xóa từ vựng '.$arr_split[1]." do từ vựng này đã được kích hoạt hoặc không tồn tại hoặc có nghĩa";
							}
						}
						break;
				}
			}
		}

		

        $orderby="MATU DESC";

        if($this->request->get('s')!=null){
        	switch ($this->request->get('s')) {
        		case '1':
        			$orderby="NGAY_CAP_NHAT DESC";
        			break;
        		case '2':
        			$orderby="NGAY_CAP_NHAT";
        			break;
        		case '3':
        			$orderby="NGHIA_HANTU";
        	}
        }

        $query=null;

        if($this->request->get('f')!=null){
        	switch ($this->request->get('f')) {
        		case '1':
        			$query=array("NV_THEM"=>$this->layout->userinfo['MANV']);
        			break;
        		case '2':
        			$query=array("KICH_HOAT"=>1);
        			break;
        		case '3':
        			$query=array("KICH_HOAT"=>0);
        			break;
        		case '4':
        			$query=array("AUDIO"=>"");
        			break;
        		case '5':
        			$query=array(new TCode('(select count(MANGHIA) from tuvung_nghia where MATU=tu_vung.MATU)=0'));
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
            $in.=$item["MATU"].",";
        }
        $in=mb_substr($in, 0,strlen($in)-1);
        $this->view->nghia=$model->getNghia($in);

        $in="";
        foreach ($this->view->nghia as $item) {
            $in.=$item["MANGHIA"].",";
        }
        $in=mb_substr($in, 0,strlen($in)-1);
        $this->view->vd_nghia=$model->getVdNghia($in);

		return $this->view();
	}

	public function themAction(){
		$this->view->title("Thêm Từ Vựng");

			
		
		if($this->request->isPost()){

			$model=$this->load->model("TuVung");
			if((int)$model->Dem(array('TIENG_ROMANJI'=>trim($this->request->post('hiragama'))))==0){
				$arr=array(
					'MATU'=>'NULL',
					'TIENG_ROMANJI'=>trim($this->request->post('romanji')),
					'HAN_TU'=>trim($this->request->post('hiragama')),
					'AUDIO'=>trim($this->request->post('audio_path')),
					'GHI_CHU'=>trim($this->request->post('ghichu')),
					'NGHIA_HANTU'=>trim($this->request->post('hantu')),
					'NGAY_CAP_NHAT'=>new TTimeNow(),
					'NV_THEM'=>$this->layout->userinfo['MANV'],
					'KICH_HOAT'=>false
				);
				$id_them=$model->Them($arr);
				if($id_them!=-1){
					if(isset($_FILES['audio']) && !empty($_FILES['audio']) && $_FILES['audio']['size']>0){
						$filename=explode("/", $this->request->post('audio_path'));
						$filename=trim($filename[count($filename)-1]);
						if(move_uploaded_file($_FILES['audio']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$this->view->baseUrl().'upload/audio/'.$filename)){
								
						}
					}
					if($this->request->post('nghia_nhat')!=null){
						$arr_viet=$this->request->post('nghia_viet');
						$arr_them=$this->request->post('listthem');
						foreach ($this->request->post('nghia_nhat') as $key => $value) {
							if(trim($arr_viet[$key])!='' && trim($value)!=''){
								$id=$model->ThemNghia(array(
									'MANGHIA'=>'NULL',
									'MATU'=>$id_them,
									'NGHIA_TIENGVIET'=>trim($arr_viet[$key]),
									'NGHIA_TIENGNHAT'=>trim($value)
								));
								if($id!=-1){
									if($this->request->post('vd_nhat')!=null){
										$arr_vd_viet=$this->request->post('vd_viet');
										$arr_id=$this->request->post('listvd');
										foreach ($this->request->post('vd_nhat') as $key2 => $value2) {
											if(trim($arr_vd_viet[$key2])!='' && trim($value2)!=''){
												if($arr_id[$key2]==$arr_them[$key]){
													$model->ThemVDNghia(array(
														'MA'=>'NULL',
														'MANGHIA'=>$id,
														'VIDU_TV'=>trim($arr_vd_viet[$key2]),
														'VIDU_TN'=>trim($value2)
													));
												}
											}
										}
									}
								}
							}
						}
					}
					$this->view->message="Thêm thành công";
				}else{
					$this->view->message="Thêm thất bại.";
					$this->view->data=$arr;
				}
			}else{
				$this->view->message="Từ ".$this->request->post('romanji')." đã có trong csdl";
			}
		}



		return $this->view();
	}
	public function suaAction(){
		if($this->request->get('id')==null){
			$this->redirect('tu-vung');
		}
		$this->view->title("Sửa Từ Vựng");
		$model=$this->load->model("TuVung");
		if($this->request->isPost()){
			$arr=array(
				'TIENG_ROMANJI'=>trim($this->request->post('romanji')),
				'HAN_TU'=>trim($this->request->post('hiragama')),
				'AUDIO'=>trim($this->request->post('audio_path')),
				'GHI_CHU'=>trim($this->request->post('ghichu')),
				'NGHIA_HANTU'=>trim($this->request->post('hantu')),
				'NGAY_CAP_NHAT'=>new TTimeNow()
			);
			if($model->Sua($arr,$this->request->get('id'))==0){
				$this->view->message="Sửa thất bại";
			}else{
				if($arr['AUDIO']!=''){
					if(isset($_FILES['audio']) && !empty($_FILES['audio']) && $_FILES['audio']['size']>0){
						$filename=explode("/", $this->request->post('audio_path'));
						$filename=trim($filename[count($filename)-1]);
						if(move_uploaded_file($_FILES['audio']['tmp_name'],$this->getRootPath().'upload/audio/'.$filename)){
								
						}
					}
				}else{
					if($this->request->post('audio_cu')!=''){
						$filename=explode("/", $this->request->post('audio_cu'));
						$filename=trim($filename[count($filename)-1]);
						unlink($this->getRootPath().'upload/audio/'.$filename);
					}
				}
				$this->view->message="Sửa thành công";
			}
		}

		

		$result=$model->getInfo($this->request->get('id')); 
		if(count($result)==1)
			$this->redirect('tu-vung');
		if($result['NV_THEM']!=$this->layout->userinfo['MANV'] && $this->layout->userinfo['LAADMIN']==0){
			$this->view->setView("error/e");
			$this->view->ten=$result['TIENG_HIRAGANA'];
		}else{
			$this->view->data=$result;
		}



		return $this->view();
	}


}