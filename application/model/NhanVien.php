<?php 

class NhanVien extends Database{
	protected $table='nhan_vien';
	public function Them($arr){
		return $this->insert($arr);
	}
	public function checkEmail($email){
		return $this->where(array('EMAIL'=>$email))->exists('EMAIL');
	}
	public function getNV($id,$like=null){
		if($like==null)
			return $this->not_where(array('MANV'=>$id))->orderBy('LAADMIN DESC,MANV DESC')->fetchAll();
		return $this->not_where(array('MANV'=>$id))->like('HOTEN',$like)->like('EMAIL',$like,'OR')->orderBy('LAADMIN DESC,MANV DESC')->fetchAll();
	}
	public function getInfo($id){
		return $this->where(array('MANV'=>$id))->row();
	}
	public function Sua($arr,$id){
		return $this->where(array('MANV'=>$id))->update($arr);
	}
	public function resetPass($id){
		return $this->where(array('MANV'=>$id))->update(array('MATKHAU'=>md5('123456')));
	}
	public function Khoa($id){
		$this->where(array('MANV'=>$id))->update(array('KHOA'=>new TCode("(CASE WHEN KHOA=0 THEN 1 ELSE 0 END)")));
	}
	public function Xoa($id){
		return $this->where(array('MANV'=>$id))->delete();
	}
}