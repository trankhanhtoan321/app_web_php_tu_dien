
<?php 
require_once ('webservice/nusoap.php');  
$client = new soapclient('http://dntudict.freevnn.com/server.php'); 


$param = array( 'date1' => '2015/7/1 1:1:1','id1'=>0,'date2'=>'2015/7/1 1:1:1','id2'=>0,'date3'=>'2015/7/1 1:1:1','id3'=>0); 
$response = $client->call("isUpdate",$param,'urn:dntu.edu.vn','urn:dntu.edu.vn#isUpdate'); 
//Process result 
if($client->fault) 
{ 
echo "FAULT: <p>Code: (".$client->faultcode."</p>"; 
echo "String: ".$client->faultstring; 
} 
else 
{ 
	header("Content-Type:text/html;charset=utf8");
	echo "isUpdate:<pre>";
	print_r(json_decode($response)); 
	echo "</pre>";
} 

$param = array( 'date' => '2015/7/1 1:1:1','id'=>0,'start'=>0); 
$response = $client->call("getHanTu",$param,'urn:dntu.edu.vn','urn:dntu.edu.vn#getHanTu'); 
//Process result 
if($client->fault) 
{ 
echo "FAULT: <p>Code: (".$client->faultcode."</p>"; 
echo "String: ".$client->faultstring; 
} 
else 
{ 
	header("Content-Type:text/html;charset=utf8");
	echo "<b>+ getHanTu:</b> ";
	print_r(json_decode($response)); 
} 

$param = array( 'date' => '2015/7/1 1:1:1','id'=>0,'start'=>0); 
$response = $client->call("getNguPhap",$param,'urn:dntu.edu.vn','urn:dntu.edu.vn#getNguPhap'); 
//Process result 
if($client->fault) 
{ 
echo "FAULT: <p>Code: (".$client->faultcode."</p>"; 
echo "String: ".$client->faultstring; 
} 
else 
{ 
	header("Content-Type:text/html;charset=utf8");
	echo "<br /><br /><br /><b>+ getNguPhap:</b> ";
	print_r(json_decode($response)); 
} 

$param = array( 'date' => '2015/7/1 1:1:1','id'=>0,'start'=>0); 
$response = $client->call("getTuVung",$param,'urn:dntu.edu.vn','urn:dntu.edu.vn#getTuVung'); 
//Process result 
if($client->fault) 
{ 
echo "FAULT: <p>Code: (".$client->faultcode."</p>"; 
echo "String: ".$client->faultstring; 
} 
else 
{ 
	header("Content-Type:text/html;charset=utf8");
	echo "<br /><br /><br /><b>+ getTuVung:</b> ";
	print_r(json_decode($response)); 
} 


?> 