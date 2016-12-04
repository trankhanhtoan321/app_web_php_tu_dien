<?php 

class View{
	private $layout_file='';
	private $view_file='';
	private $var;
	private $layout=null;
	private $baseurl=null;
	private $t;
	private $meta=array();
	private $script=array();
	private $css=array();
	private $paging=null;

	public function setLayout($layout_file){
		$this->layout_file=$layout_file;
	}

	public function setView($view_file){
		$this->view_file=$view_file;
	}

	public function __set($key,$value){
		$this->var[$key]=$value;
	}

	public function __get($key){
		if(isset($this->var[$key])){
			return $this->var[$key];
		}

		return null;
	}

	public function getVar(){
		return $this->var;
	}

	public function __unset($key=null){
		if($key!=null){
			if(isset($this->var[$key]))
			unset($this->var[$key]);
		}else{
			unset($this->var);
		}
	}

	public function disableLayout(){
		$this->layout_file='';
	}

	public function CallView(){
		if($this->layout_file!=''){
			$layout_path=APPLICATION_PATH.'/layout/'.$this->layout_file.EX_VIEW_FILE;
			if(!file_exists($layout_path)){
				return new TException('layout '.$this->layout_file.' không tồn tại',401);
			}
			require $layout_path;
		}else{
			$this->runView();
		}
		
	}

	private function content(){
		$this->runView();
	}

	private function runView(){
		$view_path=APPLICATION_PATH.'/view/'.$this->view_file.EX_VIEW_FILE;

		if(!file_exists($view_path)){
			return new TException('view '.$this->view_file.EX_VIEW_FILE.' không tồn tại',401);
		}
		require $view_path;
	}

	public function layout(){
		return $this->getLayout();
	}
	public function getLayout(){
		if($this->layout==null){
			require_once SYSTEM_PATH.'/mvc/view/Layout.php';
			$this->layout=new Layout($this);
		}

		return $this->layout;
	}
	private function renderView($path){
		$view_path=APPLICATION_PATH.'/view/'.$path.EX_VIEW_FILE;
		if(!file_exists($view_path)){
			return new TException('view '.$path.' không tồn tại',401);
		}
		require $view_path;
	}

	public function getViewFile(){
		return $this->view_file;
	}

	public function baseUrl($url=null){
		if($this->baseurl==null){
			$this->baseurl=$this->setBaseUrl();
		}
		return $this->baseurl.$url;
	}

	private function setBaseUrl(){
		$url_current=explode("/", $_SERVER['SCRIPT_NAME']);
		if($url_current[1]!="index.php")
			return "/".$url_current[1]."/";
		return $url_current[2]."/";
	}

	public function title($title=null){
		if($title==null){
			echo "<title>".$this->t."</title>\n";
		}else{
			$this->t=$title;
		}
	}

	public function appendMeta($name=null,$content=null){
		if($name!=null){
			$this->meta($name,$content);
		}
	}

	public function meta($name=null,$content=null){
		if($name==null){
			foreach ($this->meta as $item) {
				echo "<meta".$this->implodearray($item)." />\n";
			}
		}else{
			if(is_array($name)){
				$this->meta[]=$name;
			}else{
				$this->meta[]=array('name'=>$name,'content'=>$content);
			}
		}
	}

	public function appendScript($path=null){
		if($path!=null){
			$this->script($path);
		}
	}

	public function script($path=null){
		if($path==null){
			foreach ($this->script as $item) {
				echo '<script type="text/javascript" src="'.$item.'"></script>'."\n";
			}
		}else{
			$this->script[]=$path;
		}
	}

	public function appendCss($path=null){
		if($path!=null){
			$this->css($path);
		}
	}

	public function css($path=null){
		if($path==null){
			foreach ($this->css as $item) {
				echo '<link type="text/css" rel="stylesheet" href="'.$item.'"/>'."\n";
			}
		}else{
			$this->css[]=$path;
		}
	}

	private function implodearray($arr){
		$str='';
		foreach ($arr as $key => $value) {
			$str.=" ".$key.'="'.$value.'"';
		}
		return $str;
	}


	public function asset($path=''){
		return $this->baseUrl().ASSET_FOLDER."/".$path;
	}

	public function setPaging($page){
		if($page instanceof Paging){
			$this->paging=$page;
		}
	}

	public function paging(){
		if($this->paging!=null){
			return $this->paging->show();
		}
		return new TException("Paging chưa được khởi tạo. Cách khởi tạo <pre>Bên controller:\n\$paging=\$this->load->library('paging');\n\$paging->setSumRow(int tổng số lượng row);\n\$this->setItemInPage(int số lượng row trong 1 page);\n\$model->get(limit(\$paging->getStart(),\$paging->getItemInPage()))\n\$this->view->setPaging(\$paging);\nBên view:\n\$this->paging();</pre>",401);
	}
	public function __call($method,$e){
		return new TException("Không tìm thấy method ".$method." trong class View",401);
	}
}