<?php
include 'connection.php';
mysql_query(sprintf("insert into `items` (`text`) values ('%s')", mysql_real_escape_string($_POST['text'])));
if( mysql_errno()==0 ){
	echo json_encode(array('success'=>1, 'msg'=>'record added successfully'));
}else{
	echo json_encode(array('success'=>0, 'msg'=>'problem occured'));
}