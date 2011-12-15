<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'todo';

$con = mysql_connect( $host, $username, $password);
mysql_select_db( $database, $con);