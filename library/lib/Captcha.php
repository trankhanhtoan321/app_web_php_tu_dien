<?php 

require SYSTEM_PATH."lib/Session.php";

class Captcha{

	private $length=5;
	private $bg=array(231,231,231);
	private $textcolor=array(0,80,247);

	public function show(){
		$str="qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
        $md5="";
        for($i=0;$i<$this->length;$i++){
		  $md5.=substr($str, rand(0,61),1);
        }
		$s=new Session();
		$s->captcha=$md5;

		$image=ImageCreate($this->length*15,25);
        $while=ImageColorAllocate($image,$this->bg[0],$this->bg[1],$this->bg[2]);
        $black=ImageColorAllocate($image,$this->textcolor[0],$this->textcolor[1],$this->textcolor[2]);
        ImageFill($image,0,0,$while);
        ImageString($image,7,($this->length*15)/2-($this->length*5),4,$md5,$black);
        header("Content-Type:image/jpeg");
        ImageJpeg($image);
        ImageDestroy($image);
	}

	public function setLength($l){
		if(is_integer($l)){
			$this->length=$l;
		}else{
			return new TException("Lỗi function setLength trong Library captcha.<br /><br />Cấu trúc: <pre>public function setLength(number length)\n\nEX: setLength(5);</pre>",401);
		}
	}

	public function setBackground($bg){
		if(is_array($bg)){
			$this->bg=$bg;
		}else{
			return new TException("Lỗi function setBackground cho captcha.<br /><br />Cấu trúc: <pre>public function setBackground(array background)\n\nEX: setBackground(array(0,0,0))\n Với 0,0,0 là mã RGB</pre>",401);
		}
	}

	public function setTextColor($textcolor){
		if(is_array($textcolor)){
			$this->textcolor=$textcolor;
		}else{
			return new TException("Lỗi function setTextColor cho captcha.<br /><br />Cấu trúc: <pre>public function setTextColor(array textcolor)\n\nEX: setTextColor(array(0,0,0))\n Với 0,0,0 là mã RGB</pre>",401);
		}
	}
}