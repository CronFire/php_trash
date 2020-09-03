<?php
if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}
$connect_error = 'MySQL connection error!';

$mysql_host = '';
$mysql_user = '';
$mysql_pass = '';
$mysql_db = '';
$mysql_table = '';


if (!mysql_connect($mysql_host, $mysql_user, $mysql_pass) || !mysql_select_db($mysql_db)) {
    die($connect_error);
}
?>