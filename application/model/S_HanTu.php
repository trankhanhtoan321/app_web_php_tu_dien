<?php 

class HanTu extends Query{

	public function getData($date,$id,$start){
		$data=$this->Select("select MAHT,TU,BO,BO_HUAN,BO_AM,SONET,JLPT,BO_THANH_PHAN,NGHIA,GIAI_NGHIA,NGAY_CAP_NHAT from han_tu where KICH_HOAT=1 AND (MAHT>".$id." OR NGAY_CAP_NHAT>'".$date."') LIMIT ".$start.",50");
		return json_encode($data);
	}
}