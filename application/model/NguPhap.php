<?php 

class NguPhap extends Database{
	protected $table="ngu_phap";

	public function Them($arr){
		return $this->insert($arr);
	}

	public function KiemTra($nguphap){
		return $this->where(array("TIENG_NHAT"=>$nguphap))->count()==0;
	}

	public function getData($start,$count,$order,$query,$like){
		if($like==null)
		return $this->where($query)->select('ngu_phap.*,nhan_vien.HOTEN')->join('nhan_vien','ngu_phap.NV_GUI=nhan_vien.MANV')->limit($start,$count)->orderBy($order)->fetchAll();
		else
		return $this->where($query)->like('TIENG_NHAT',$like)->like('Y_NGHIA',$like,'OR')->select('ngu_phap.*,nhan_vien.HOTEN')->join('nhan_vien','ngu_phap.NV_GUI=nhan_vien.MANV')->limit($start,$count)->orderBy($order)->fetchAll();
	}

	public function Dem($q=null,$like=null){
		if($like==null)
			return $this->where($q)->count();
		else
			return $this->where($q)->like('TIENG_NHAT',$like)->like('Y_NGHIA',$like,'OR')->count('MANP');
	}

	public function ThemVD($arr){
		return $this->from('nguphap_vd')->insert($arr);
	}

	public function getInfo($id){
		return $this->where(array('MANP'=>$id))->row();
	}

	public function Sua($arr,$id){
		return $this->where(array("MANP"=>$id))->update($arr);
	}

	public function BatTatKichHoat($arr,$id){
		return $this->where(array("MANP"=>$id))->update($arr);
	}

	public function Xoa($id){
		return $this->where(array("MANP"=>$id,'KICH_HOAT'=>0))->delete();
	}

	public function getVD($in){
		return $this->from('nguphap_vd')->in('MANP',$in)->fetchAll();
	}

	public function XoaVD($id){
		return $this->from("nguphap_vd")->where(array("MAVD"=>$id))->delete();
	}

	public function SuaVD($data,$id){
		return $this->from("nguphap_vd")->where(array("MAVD"=>$id))->update($data);
	}
}