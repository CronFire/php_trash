<?php
if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}
$connect_error = 'MySQL connection error!';

$mysql_host = ''; //Хост БД
$mysql_user = '';//Юзер БД
$mysql_pass = '';//Пасс БД
$mysql_db = '';//БД
$mysql_table = '';//Таблица БД
$balance_min = '1000';//Минимальный баланс, если у игрока денег меньше этого значения - показываться в топе не будет.

if (!mysql_connect($mysql_host, $mysql_user, $mysql_pass) || !mysql_select_db($mysql_db)) {
    die($connect_error);
}

?>