<?php 

define("APPLICATION_PATH","application/");
define("SYSTEM_PATH","library/");
define("EX_VIEW_FILE",".phtml");
define("ASSET_FOLDER","public");

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once 'webservice/nusoap.php'; 
require_once SYSTEM_PATH."db/ConnectDb.php";
require_once SYSTEM_PATH."db/Query.php";


$namespace='urn:dntu.edu.vn';
		
$server    = new soap_server();
$server->configureWSDL('server dntu', $namespace);
$server->xml_encoding = 'utf-8';
$server->soap_defencoding = 'utf-8';
			
		
		
$server->register('isUpdate',
	array('date1'=>'xsd:string','id1'=>'xsd:int','date2'=>'xsd:string','id2'=>'xsd:int','date3'=>'xsd:string','id3'=>'xsd:int'),      
	array('return' => 'xsd:string'), 
    $namespace,                 
    $namespace.'#isUpdate',           
    'rpc',                    
    'encoded',                   
    'Says hello to the caller'    
);
$server->register('getHanTu',
	array('date'=>'xsd:string','id'=>'xsd:int','start'=>'xsd:int'),      
	array('return' => 'xsd:string'), 
    $namespace,                 
    $namespace.'#getHanTu',           
    'rpc',                    
    'encoded',                   
    'Says hello to the caller'    
);
$server->register('getNguPhap',
	array('date'=>'xsd:string','id'=>'xsd:int','start'=>'xsd:int'),      
	array('return' => 'xsd:string'), 
    $namespace,                 
    $namespace.'#getNguPhap',           
    'rpc',                    
    'encoded',                   
    'Says hello to the caller'    
);
$server->register('getTuVung',
	array('date'=>'xsd:string','id'=>'xsd:int','start'=>'xsd:int'),      
	array('return' => 'xsd:string'), 
    $namespace,                 
    $namespace.'#getTuVung',           
    'rpc',                    
    'encoded',                   
    'Says hello to the caller'    
);

$config=require_once "config/config.php";

$GLOBALS['db'] = $config['db'];

function isUpdate($date1,$id1,$date2,$id2,$date3,$id3){
	require_once APPLICATION_PATH."model/S_Update.php";

	$model=new Update($GLOBALS['db']);

	return $model->check($date1,$id1,$date2,$id2,$date3,$id3);
}

function getHanTu($date,$id,$start){
	require_once APPLICATION_PATH."model/S_HanTu.php";

	$model=new HanTu($GLOBALS['db']);

	return $model->getData($date,$id,(int)$start);
}

function getNguPhap($date,$id,$start){
	require_once APPLICATION_PATH."model/S_NguPhap.php";

	$model=new NguPhap($GLOBALS['db']);

	return $model->getData($date,$id,(int)$start);
}

function getTuVung($date,$id,$start){
	require_once APPLICATION_PATH."model/S_TuVung.php";

	$model=new TuVung($GLOBALS['db']);

	return $model->getData($date,$id,(int)$start);
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

?>