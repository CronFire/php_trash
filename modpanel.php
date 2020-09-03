<?php

define('_CFEXEC', true);

require('engine/modules/server/support/config.php');

echoheader("", "");
####

//functions

function getName($id) {
    mysql_select_db("cfMC_LogBlock") or die("жопа");
    $query = mysql_query("SELECT `playername` FROM `lb-players` WHERE `playerid` = '$id' LIMIT 1");
    return mysql_result($query, 0);
}

function getBundleID($name) {
    mysql_select_db("cronfiremc");
    $query = mysql_query("SELECT `id` FROM `cfSessionsUsers` WHERE `user` = '$name'");
    return mysql_result($query, 0);
}

####

?>

    <h1>Панель модератора</h1><br>
    <form name="dupe" method="post" action="">
        Ник: <input type="text" name="nickname">
        <input type="submit" name="dupe">
        <br>Ищем по подсети? <input type="checkbox" name="network">
    </form>
    <br>
    <hr>

<?php
if (!(empty($_POST['dupe']))) {
    mysql_select_db("cfMC_BanManager");
    if (!(empty($_POST['nickname']))) {
        $playername = mysql_real_escape_string($_POST['nickname']);
        $table = 'banlistip';
        $sql = mysql_query("SELECT `ip` FROM $table WHERE `player` = '$playername'") or trigger_error(mysql_error() . " " . $sql);
        if (empty($_POST['network'])) {
            $userip = mysql_result($sql, 0);
            $sql = mysql_query("SELECT `player` FROM $table WHERE `ip` = '$userip' ORDER BY `player` ASC");
        } else {
            $userip = mysql_result($sql, 0);
            $userip = explode(".", $userip);
            unset($userip['2']);
            unset($userip['3']);
            $userip = implode(".", $userip);
            $sql = mysql_query("SELECT * FROM $table WHERE `ip` LIKE '$userip%' ORDER BY `player` ASC");
        }
        echo 'Мультиаккаунт: <br><br>';
        echo "Ник: <b>$playername</b><br>";
        echo "IP: <b>$userip</b><br>";
        if (!(mysql_num_rows($sql) <= 1)) {
            echo "Твинки: ";
            while ($data = mysql_fetch_assoc($sql)) {
                $name = $data['name'];
                if (!(empty($_POST['network']))) {
                    $ip = $data['lastip'];
                } else {
                    $ip = $userip; //TODO:Fix wtf
                }
                echo '<br>';
                if (!($name === $playername)) {
                    echo "<br><b>Ник:</b> $name";
                    if (!(empty($_POST['network']))) {
                        echo " | <b>IP:</b> $ip";
                    }
                }
            }
        } else {
            echo '<b>Твинков нет!</b>';
        }
    } else {
        echo 'Вы не ввели никнейм!';
    }
}

echo '<br><br><br><br><br>';
echo 'Логи:<br>';
echo '<form name="logsearch" method="post" action="">';
echo 'Ник: <input type="text" name="nickname"><br>';
echo 'Мир:<br>';
echo '<input name="world" type="radio" value="world">world';
echo '<input name="world" type="radio" value="world_nether">world_nether';
echo '<input name="world" type="radio" value="world_the_end">world_the_end';
echo '<input name="world" type="radio" value="world_mine">world_mine';
echo '<input name="world" type="radio" value="world_test">world_test';
echo '<br>';
echo 'Ищем:<br>';
echo '<input name="logtype" type="radio" value="blocks" disabled>Блоки';
echo '<input name="logtype" type="radio" value="chat">Чат';
echo '<input name="logtype" type="radio" value="kills" disabled>ПВП';
echo '<input name="logtype" type="radio" value="chest" disabled>Сундуки';
echo '<input name="logtype" type="radio" value="sign" disabled>Таблички';
echo '<br>Лимит:<input type="text" name="length" value="100" size="3"> <br>';
echo 'Дата: ';
echo '<input name="month" type="text" size="3" disabled>m';
echo '<input name="day" type="text" size="3" disabled>d';
echo '<input name="hour" type="text" size="3" disabled>h';
echo '<input name="minute" type="text" size="3" disabled>m';
echo '<br><input name="coords" type="checkbox" disabled>Координаты';

echo '<br><input name="logsearch" type="submit">';
echo '</form>';


if (!(empty($_POST['logsearch']))) {
    mysql_select_db("cfMC_LogBlock") or die("жопа");
    mysql_set_charset("UTF8");

    if ($_POST['length'] <= 100) {

        $world = mysql_real_escape_string($_POST['world']);
        $playername = mysql_real_escape_string($_POST['nickname']);
        $limit = (int)mysql_real_escape_string($_POST['length']);
        $query1 = mysql_query("SELECT `playerid` FROM `lb-players` WHERE `playername` = '$playername' LIMIT 1");
        $playerid = (int)mysql_result($query1, 0);

        if (strtolower($_POST['logtype']) !== 'blocks' && strtolower($_POST['logtype']) !== 'chat') {

            $logtype = mysql_real_escape_string($_POST['logtype']);

            $table = "lb-$world-$logtype";

            $query = mysql_query("SELECT * FROM $table WHERE `playerid` = '$playerid' LIMIT $limit");

        } else if (strtolower($_POST['logtype']) == 'chat') {
            mysql_select_db("cronfiremc");
            mysql_set_charset("utf8");
            if (!(empty($playername))) {
                $query = mysql_query("SELECT * FROM `cfLogChat` WHERE `player` = '$playername' ORDER BY `id` DESC LIMIT $limit") or die(mysql_error());
            } else {
                $query = mysql_query("SELECT * FROM `cfLogChat` ORDER BY `id` DESC LIMIT $limit") or die(mysql_error());
            }

        } else if (strtolower($_POST['logtype']) == 'blocks') {

            $table = "lb-$world";

            $query = mysql_query("SELECT * FROM $table WHERE `playerid` = '$playerid' LIMIT $limit");

        } else {
            $query = mysql_query("SELECT * FROM $table WHERE `playerid` = '$playerid' LIMIT $limit");
        }


        echo "<table width=100% border=1 cellpadding=3 cellspacing=1>";
        echo '<tr>';
        echo '<td>Дата(через жопу)</td>';
        echo '<td>Игрок</td>';
        echo '<td>Сообщение</td>';
        echo '<td>Мир</td>';
        echo '</tr>';

        while ($data = mysql_fetch_assoc($query)) {
            echo '<tr>';
            if (strtolower($_POST['logtype']) == 'chat') {
                echo "<td>" . cf_api::dateconvert($data['time']) . "</td>";
                echo "<td>" . $data['player'] . "</td>";
                echo "<td>" . cf_api::toUnicode($data['chat']) . "</td>";


                echo "<td>" . $data['world'] . "</td>";
            } else {
                echo "<td>" . $data['date'] . "</td>";
                echo "<td>" . getName($data['playerid']) . "</td>";
                echo "<td>" . $data['message'] . "</td>";
                echo "<td>ХЗ</td>";
            }
            echo '</tr>';

        }
        echo '</table>';

    } else {

        echo 'Лимит не может быть более 100 строк :)';
    }

}


// HANDLER CODE ENDS HERE


echo '<br><br><br><br><br>';
echofooter();


mysql_close();

unset($config);


?>