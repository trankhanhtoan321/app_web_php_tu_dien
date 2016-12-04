<?php

class Login extends Database{
	protected $table="nhan_vien";
	public function check($email,$password){
		return $this->select("MANV,HOTEN,LAADMIN,KHOA")->where(array("EMAIL"=>$email,"MATKHAU"=>md5($password)))->row();
	}
	public function updatePass($email,$pass){
		return $this->where(array("EMAIL"=>$email))->update(array("MATKHAU"=>md5($pass)));
	}
	public function checkMail($email){
		return $this->where(array("EMAIL"=>$email))->exists();
	}
}