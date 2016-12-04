<?php 

class TuVung extends Database{
	protected $table="tu_vung";
	public function getData($start,$count,$order,$query,$like){
		if($like==null)
		return $this->where($query)->select('tu_vung.*,nhan_vien.HOTEN')->join('nhan_vien','tu_vung.NV_THEM=nhan_vien.MANV')->limit($start,$count)->orderBy($order)->fetchAll();
		else
		return $this->where($query)->like('TIENG_VIET',$like)->like('TIENG_KANJI',$like,'OR')->select('tu_vung.*,nhan_vien.HOTEN')->join('nhan_vien','tu_vung.NV_THEM=nhan_vien.MANV')->limit($start,$count)->orderBy($order)->fetchAll();
	}
	public function Them($arr){
		return $this->insert($arr);
	}
	public function getInfo($id){
		return $this->where(array('MATU'=>$id))->row();
	}
	public function Sua($arr,$id){
		return $this->where(array("MATU"=>$id))->update($arr);
	}
	public function Dem($q=null,$like=null){
		if($like==null)
			return $this->where($q)->count();
		else
			return $this->where($q)->like('TIENG_VIET',$like)->like('TIENG_KANJI',$like,'OR')->count('MATU');
	}
	public function ThemNghia($arr){
		return $this->from('tuvung_nghia')->insert($arr);
	}
	public function ThemVDNghia($arr){
		return $this->from('tuvung_vidu_nghia')->insert($arr);
	}
	public function BatTatKichHoat($arr,$id){
		return $this->where(array("MATU"=>$id))->update($arr);
	}
	public function Xoa($id){
		return $this->where(array("MATU"=>$id,'KICH_HOAT'=>0))->delete();
	}
	public function getNghia($in){
		return $this->from('tuvung_nghia')->in('MATU',$in)->fetchAll();
	}
	public function XoaNghia($id){
		return $this->from('tuvung_nghia')->where(array('MANGHIA'=>$id))->delete();
	}
	public function SuaNghia($arr,$id){
		return $this->from('tuvung_nghia')->where(array('MANGHIA'=>$id))->update($arr);
	}
	public function getVdNghia($in){
		return $this->from('tuvung_vidu_nghia')->in('MANGHIA',$in)->fetchAll();
	}
	public function SuaVDNghia($arr,$id){
		return $this->from('tuvung_vidu_nghia')->where(array('MA'=>$id))->update($arr);
	}
	public function XoaVDNghia($id){
		return $this->from('tuvung_vidu_nghia')->where(array('MA'=>$id))->delete();
	}
}