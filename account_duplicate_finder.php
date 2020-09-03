<?php
error_reporting(0);
$host = '';
$login = '';
$password = '';
$database = '';

$connect = mysql_connect($host, $login, $password) or die("MySQL server currently down ");
mysql_query('SET NAMES utf8', $connect);
mysql_query('SET CHARACTER SET utf8', $connect);
mysql_query('SET COLLATION_CONNECTION="utf8_bin"', $connect);
mysql_select_db($database, $connect) or die ("MySQL server currently down!");
echo '<title>Тулза :3</title>';
echo 'Узнать все ники на IP<br />Введите IP:<br />
  <form id="form1" name="form1" method="post" action="">
  <input type="text" name="userip" id="userip" maxlength="20" size="20"/>
  <input type="submit" name="view" id="view" value="Смотреть" /></form>';

$userip = mysql_escape_string($_POST['userip']);
$userip = htmlspecialchars($userip);

if (isset($_POST['view'])) {

    if (!empty($userip)) {

        $query = mysql_query("SELECT * FROM `banlistip` 
		   WHERE `lastip` = '$userip' LIMIT 300")
        or die(mysql_error());


        while ($string = mysql_fetch_array($query)) {
            echo '' . $string['name'] . '  |  ' . $string['lastip'] . ' <br />';
        }

    } else {
        echo "Введите IP";
    }
}

echo '<hr>';

echo 'Узнать IP юзера<br />Введите ник:';

echo '<form id="form2" name="form2" method="post" action="">
  <input type="text" name="username" id="username" maxlength="20" size="20"/>
  <input type="submit" name="view2" id="view2" value="Смотреть" /></form>';

$username = mysql_escape_string($_POST['username']);
$username = htmlspecialchars($username);

if (isset($_POST['view2'])) {

    if (!empty($username)) {

        $query = mysql_query("SELECT * FROM `banlistip` 
		   WHERE `name` = '$username' LIMIT 300")
        or die(mysql_error());


        while ($string = mysql_fetch_array($query)) {
            echo '' . $string['name'] . ' | ' . $string['lastip'] . ' <br />';
        }

    } else {
        echo "Введите Ник";
    }
}
?>