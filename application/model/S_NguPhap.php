<?php 

class NguPhap extends Query{

	public function getData($date,$id,$start){
		$data=$this->Select("select MANP,TIENG_NHAT,Y_NGHIA,CAU_TRUC,MIEU_TA,CHU_Y,JLPT,NGAY_CAP_NHAT from ngu_phap where KICH_HOAT=1 AND (MANP>".$id." OR NGAY_CAP_NHAT>'".$date."') LIMIT ".$start.",50");
		$in="";
        foreach ($data as $item) {
            $in.=$item[0].",";
        }
        $in=mb_substr($in, 0,strlen($in)-1);
        $data_sub=$this->Select("select * from nguphap_vd where MANP in(".$in.")");
        foreach ($data as &$value) {
        	$value[8]=[];
        	foreach ($data_sub as $key => $value2) {
        		if($value2[1]==$value[0]){
        			$value[8][]=$value2;
        			unset($data_sub[$key]);
        		}
        	}
        }

		return json_encode($data);
	}
}