<?php 

class TuVung extends Query{
    public function getData($date,$id,$start){
        $data=$this->Select("select MATU,TIENG_ROMANJI,HAN_TU,AUDIO,GHI_CHU,NGHIA_HANTU,NGAY_CAP_NHAT from tu_vung where KICH_HOAT=1 AND (MATU>".$id." OR NGAY_CAP_NHAT>'".$date."') LIMIT ".$start.",50");
        $in="";
        foreach ($data as $item) {
            $in.=$item[0].",";
        }
        $in=mb_substr($in, 0,strlen($in)-1);
        $data_sub=$this->Select("select * from tuvung_nghia where MATU in(".$in.")");
        $in2="";
        foreach ($data_sub as $item) {
             $in2.=$item[0].",";
        }
        $in2=mb_substr($in2, 0,strlen($in2)-1);
        $data_sub2=$this->Select("select * from tuvung_vidu_nghia where MANGHIA in(".$in2.")");
        foreach ($data as &$value) {
            $value[7]=[];
            foreach ($data_sub as $key => $value2) {
                if($value2[1]==$value[0]){
                    $value2[4]=[];
                    foreach ($data_sub2 as $key2=>$value3) {
                        if($value2[0]==$value3[1]){
                            $value2[4][]=$value3;
                            unset($data_sub2[$key2]);
                        }
                    }
                    $value[7][]=$value2;
                    
                    unset($data_sub[$key]);
                }
            }
        }
        
        
       
        
       

        return json_encode($data);
    }
}