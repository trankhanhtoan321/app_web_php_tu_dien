<?php 

class Update extends Query{
	public function check($date1,$id1,$date2,$id2,$date3,$id3){
		$arr=array();

		$hantu=$this->Row("select count(MAHT) as c from han_tu where KICH_HOAT=1 AND (MAHT>".$id1." or NGAY_CAP_NHAT>'".$date1."')");
		if($hantu!=null){
			$arr[0]=$hantu['c'];
		}
		$nguphap=$this->Row("select count(MANP) as c from ngu_phap where KICH_HOAT=1 AND (MANP>".$id2." or NGAY_CAP_NHAT>'".$date2."')");
		if($nguphap!=null){
			$arr[1]=$nguphap['c'];
		}
		$tuvung=$this->Row("select count(MATU) as c from tu_vung where KICH_HOAT=1 AND (MATU>".$id3." or NGAY_CAP_NHAT>'".$date3."')");
		if($tuvung!=null){
			$arr[2]=$tuvung['c'];
		}

		return json_encode($arr);
	}
}