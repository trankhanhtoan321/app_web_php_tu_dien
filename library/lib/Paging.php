<?php 

class Paging{
	private $sum=-1;
	private $count=null;
	private $show=false;
	public function show(){
		if($this->sum==-1)
			return new TException("Paging chưa khởi tạo tống số row. Cách khởi tạo: <pre>\$paging->setSumRow(\$sum);</pre>",401);
		if($this->count==null){
			return new TException("Paging chưa khởi tạo tống số item trong 1 trang. Cách khởi tạo: <pre>\$paging->setItemInPage(\$item);</pre>",401);
		}

		echo "<link href=\"".SYSTEM_PATH."lib/style/page.css\" rel=\"stylesheet\"><div class=\"page clearfix\">";
		$sl=(int)($this->sum/$this->count);
		if($this->sum%$this->count==0)
			$sl--;
		$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if(isset($_GET['page'])){
			$url=preg_replace("/page=[0-9]*$/", "", $url);
		}else{ 
			if(count(explode("?", $url))>1){
				$url=$url.="&";
			}else{
				$url=$url.="?";
			}
		}

		$page_current=0;
		if(isset($_GET['page'])){
            $page_current=(int)$_GET['page'];
        }

		if($sl>=1){


			if($page_current!=0){
				echo '<a href="'.$url.'page=0" class="pagestart"></a>';
				if($page_current!=1)
				echo '<a href="'.$url.'page='.($page_current-1).'" class="pageprev"></a>';
			}
			if($sl<4){
				for($i=0;$i<=$sl;$i++){
					if($i!=$page_current)
						echo '<a class="pageitem" href="'.$url.'page='.$i.'">'.($i+1).'</a>';
					else
						echo '<span>'.($i+1).'</span>';
				}
			}else{
				if($page_current>1){
					echo '<a class="pageitem" href="'.$url.'page=0">1</a>';
				}

				$max=$page_current+2;

				if($max>$sl){
					$max=$sl;
				}

				$min=$page_current-1;

				if($min==-1){
					$min=0;
				}

				if($page_current==0)
					$max+=3;

				if($page_current==$sl){
					$min-=3;
				}

				if($page_current>1 && $page_current-1>1){
					echo '<i>...<ul>';
					for($index=1;$index<$min;$index++){
						echo '<a href="'.$url.'page='.$index.'">'.($index+1).'</a>';
					}
					echo '</ul></i>';
				}



				for($i=$min;$i<=$max;$i++){
					if($i!=$page_current)
						echo '<a class="pageitem" href="'.$url.'page='.$i.'">'.($i+1).'</a>';
					else
						echo '<span>'.($i+1).'</span>';
				}

				if($max+1<$sl){
					echo '<i>...<ul>';
					for($index=$max+1;$index<$sl;$index++){
						echo '<a href="'.$url.'page='.$index.'">'.($index+1).'</a>';
					}
					echo '</ul></i>';
				}

				if($max<$sl){
					echo '<a class="pageitem" href="'.$url.'page='.$sl.'">'.($sl+1).'</a>';
				}
			}

			if($page_current!=$sl){
				if($page_current!=$sl-1)
				echo '<a href="'.$url.'page='.($page_current+1).'" class="pagenext"></a>';
				echo '<a href="'.$url.'page='.$sl.'" class="pageend"></a>';
			}
			$den=($page_current*$this->count)+$this->count;
			if($den>$this->sum)
				$den=$this->sum;
				if($this->show)
				echo "<b>Hiển thị ".(($page_current*$this->count)+1)." đến ".$den." trên ".$this->sum."</b>";
			

		}
	}
	public function setSumRow($sum){
		if(!is_integer($sum)){
			return new TException("Không tìm thấy function setSumRow(".gettype($sum)."). Vui lòng thử với: <pre>public function setSumRow(int \$sum)</pre>",401);
		}
		$this->sum=$sum;
	}
	public function setItemInPage($item){
		if(!is_integer($item)){
			return new TException("Không tìm thấy function setItemInPage(".gettype($item)."). Vui lòng thử với: <pre>public function setItemInPage(int \$item)</pre>",401);
		}
		$this->count=$item;
	}
	public function getItemInPage(){
		if($this->count==null){
			return new TException("Paging chưa khởi tạo tống số item trong 1 trang. Cách khởi tạo: <pre>\$paging->setItemInPage(\$item);</pre>",401);
		}
		return $this->count;
	}
	public function showLabel($f=true){
		$this->show=$f;
	}

	public function getStart(){
		if($this->count==null){
			return new TException("Paging chưa khởi tạo tống số item trong 1 trang. Cách khởi tạo: <pre>\$paging->setItemInPage(\$item);</pre>",401);
		}
		$page_current=0;
		if(isset($_GET['page'])){
            $page_current=(int)$_GET['page'];
        }
        return $page_current*$this->count;
	}
}