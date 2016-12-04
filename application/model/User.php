<?php 

class User extends Database{
	protected $table="nhan_vien";

	public function getPass($ma){
		return $this->select('MATKHAU')->where(array('MANV'=>$ma))->column();
	}

	public function changePass($id,$pass){
		return $this->where(array('MANV'=>$id))->update(array('MATKHAU'=>md5($pass)));
	}

	public function getInfo($id){
		return $this->where(array('MANV'=>$id))->row();
	}

	public function ChangeProfile($arr,$id){
		return $this->where(array('MANV'=>$id))->update($arr);
	}
}