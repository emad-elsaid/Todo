<?php
include 'connection.php';
mysql_query(sprintf("update `items` set `%s`='%s' where `id`='%d'",
				mysql_real_escape_string($_POST['column']),
				mysql_real_escape_string($_POST['value']),
				mysql_real_escape_string($_POST['id'])
			));
echo ( mysql_errno()==0 )? 'Record updated' : 'Error Encountered: '.mysql_error();