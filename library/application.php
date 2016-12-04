<?php 

require_once SYSTEM_PATH."exception/TException.php";
require_once SYSTEM_PATH."/mvc/controller/Controller.php";
require_once SYSTEM_PATH."/mvc/TBoostrap.php";

class Application{
	public function __construct(){

	}
	public function run(array $config){
		$arr=array();

		if(isset($_SERVER['PATH_INFO'])){
			$arr_url=explode("/", $_SERVER['PATH_INFO']);

			if(!$this->rewriteUrl($arr,$arr_url,$config)){

				$arr['controller']=$this->create_controller($arr_url[1]);

				$at="index";
				if(isset($arr_url[2])){
					$at=$arr_url[2];
					$arr['action']=strtolower($arr_url[2])."Action";
				}else{
					$arr['action']='indexAction';
				}

				$arr['view_file']=$arr_url[1]."/".$at;
			}
		}else{
			if(isset($config['default'])){
				$ct="index";
				if(isset($config['default']['controller'])){
					$arr['controller']=$this->create_controller($config['default']['controller']);
					$ct=$config['default']['controller'];
				}else{
					$arr['controller']='IndexController';
				}
				$at='index';
				if(isset($config['default']['action'])){
					$arr['action']=strtolower($config['default']['action'])."Action";
					$at=$config['default']['action'];
				}else{
					$arr['action']='indexAction';
				}
				$arr['view_file']=$ct."/".$at;
			}else{
				$arr['controller']='IndexController';
				$arr['action']='indexAction';
				$arr['view_file']='index/index';
			}
		}

		$this->CallController($arr,$config);
		
	}

	private function rewriteUrl(&$arr,$arr_url,$config){
		$flag=false;

		if(isset($config['route'])){
			$url_str=implode('', $arr_url);

			foreach ($config['route'] as $key => $value) {
				if($key==$url_str){
					$arr['controller']=$this->create_controller($value['controller']);
					$arr['action']=strtolower($value['action'])."Action";
					$arr['view_file']=$value['controller'].'/'.$value['action'];
					$flag=true;
					break;
				}
				$match=$this->testRegex($key,$url_str);
				if($match!=null){
					$arr['controller']=$this->create_controller($value['controller']);
					$arr['action']=strtolower($value['action'])."Action";
					$arr['view_file']=$value['controller'].'/'.$value['action'];
					$flag=true;
					for($i=1;$i<count($match);$i++){
						if(isset($value['param'][$i-1]))
							$_GET[$value['param'][$i-1]]= $match[$i];	
					}
					break;
				}
			}
		}

		return $flag;
	}

	private function testRegex($pattern,$subject){
		if(preg_match("/".$pattern."/", $subject,$match))
			return $match;
		return null;
	}

	private function CallController(array $arr,array $config){
		$controller_path=APPLICATION_PATH.'controller/'.$arr['controller'].'.php';

		if(!file_exists($controller_path))
			return new TException('Không tìm thấy controller '.$arr['controller'],401);

		require_once $controller_path;

		if(!class_exists($arr['controller'])){
			return new TException('Không tìm thấy controller '.$arr['controller'],401);
		}

		
		$controller=new $arr['controller']();

		if(!is_subclass_of($controller, 'Controller')){
			return new TException('Controller '.$arr['controller'].' phải kế thừa lớp Controller. <br />VD: <pre>class HomeController extend Controller{<br />	public function init(){<br /><br />	}<br />}</pre>',401);
		}

		if(!method_exists($controller, $arr['action'])){
			return new TException('action '.$arr['action'].' không tồn tại trong controller '.$arr['controller'],401);
		}

		$boostrap_file=APPLICATION_PATH.'/Boostrap.php';

		if(!file_exists($boostrap_file)){
			return new TException('Không tìm thấy boostrap file',401);
		}

		require $boostrap_file;

		if(!class_exists("Boostrap")){
			return new TException('Không tìm thấy class boostrap file',401);
		}
		if(isset($config['layout'])){
			$controller->setLayout($config['layout']);
		}
		$controller->setView($arr['view_file']);

		$boostrap=new Boostrap($config,$controller,$arr['controller'],$arr['action']);

		if(isset($config['db'])){
			$controller->setDb($config['db']);
		}
		
		$controller->init();
		
		$controller->$arr['action']();
		
		
		
		
	}

	private function create_controller($controller){
		$arr=explode("-", $controller);
		$controller_name='';
		foreach ($arr as $value) {
			$controller_name.=ucfirst(strtolower($value));
		}

		return $controller_name."Controller";
	}

}