<?php
include 'connection.php';
mysql_query(sprintf("delete from `items` where `id`='%d'",
					mysql_real_escape_string($_POST['id'])
				));
echo ( mysql_errno()==0 )? 'record removed successfully':'error occured : '.mysql_error();
