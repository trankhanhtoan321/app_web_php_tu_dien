<?php 

class HanTu extends Database{
	protected $table='han_tu';
	public function Them($arr){
		return $this->insert($arr);
	}
	public function KiemTraTuTonTai($tu){
		return (int)$this->where(array('TU'=>$tu))->count()==0;
	}
	public function getData($start,$count,$order,$query,$like){
		if($like==null)
		return $this->where($query)->select('han_tu.*,nhan_vien.HOTEN')->join('nhan_vien','han_tu.NV_GUI=nhan_vien.MANV')->limit($start,$count)->orderBy($order)->fetchAll();
		else
		return $this->where($query)->like('TU',$like)->like('BO',$like,'OR')->select('han_tu.*,nhan_vien.HOTEN')->join('nhan_vien','han_tu.NV_GUI=nhan_vien.MANV')->limit($start,$count)->orderBy($order)->fetchAll();
	}
	public function Dem($q=null,$like=null){
		if($like==null)
			return $this->where($q)->count();
		else
			return $this->where($q)->like('TU',$like)->like('BO',$like,'OR')->count('MAHT');
	}
	public function getInfo($id){
		return $this->where(array('MAHT'=>$id))->row();
	}
	public function Sua($arr,$id){
		return $this->where(array("MAHT"=>$id))->update($arr);
	}
	public function BatTatKichHoat($arr,$id){
		return $this->where(array("MAHT"=>$id))->update($arr);
	}
	public function Xoa($id){
		return $this->where(array("MAHT"=>$id,'KICH_HOAT'=>0))->delete();
	}
}