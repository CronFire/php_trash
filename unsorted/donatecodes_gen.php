<form name="keycreate" method="post">
    <input type="text" name="number" value="20">
    <input type="submit" value="Генерировать">
    <form><br><br>
<?php

$dbhost = '';
$dbuser = '';
$dbpass = '';
$dbbase = '';
$connect_error = 'DB connection error.';


mysql_connect($dbhost, $dbuser, $dbpass) or die($connect_error);
mysql_select_db($dbbase) or die($connect_error);

echo generate_password(intval($_POST['number']));
function generate_password($number) {
    $arr = array('a', 'b', 'c', 'd', 'e', 'f',
        'g', 'h', 'i', 'j', 'k', 'l',
        'm', 'n', 'o', 'p', 'r', 's',
        't', 'u', 'v', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F',
        'G', 'H', 'I', 'J', 'K', 'L',
        'M', 'N', 'O', 'P', 'R', 'S',
        'T', 'U', 'V', 'X', 'Y', 'Z',
        '1', '2', '3', '4', '5', '6',
        '7', '8', '9', '0');
    $pass = "";

    for ($i = 0; $i < $number; $i++) {
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
        //$tip_dost  = 4; // 0 = 100p  / 1 = 200p / 2 = 50p / 3 = 25p / 4 = 500p /
    }
    if ($pass != '') {
        $sql = mysql_query("INSERT INTO donate_codes VALUES (NULL,'$pass','0','0')");
        mysql_query($sql) or trigger_error(mysql_error() . " " . $sql);
        echo $sql;
        $filename = 'codes.txt';
        $fp = fopen($filename, 'a');
        fwrite($fp, $pass);
        fwrite($fp, "\n");
        fclose($fp);
    }
    return $pass;
}

?>