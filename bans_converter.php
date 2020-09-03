<?php
/*
 * COPYRIGHT (c) 2013 Zeluboba (Roman Zabaluev)
 * This file is part of BansConverter
 * Package: ${PACKAGE_NAME}
 * Date: 06.08.13
 * Time: 3:40
 * DO NOT DISTRIBUTE.
 */

error_reporting(E_ALL);

$config = array();

$config['mysql_host'] = "cronfire.ru";
$config['mysql_port'] = "3306";
$config['mysql_user'] = "";
$config['mysql_pass'] = "";
$config['mysql_db'] = "cronfiremc";
$config['mysql_new_db'] = "cfMC_BanManager";
$config['mysql_tprefix'] = "bm_";
$config['mysql_ubprefix'] = "";
$ubprefix = $config['mysql_ubprefix'];
$prefix = $config['mysql_tprefix'];
$newdb = $config['mysql_new_db'];
$olddb = $config['mysql_db'];

$connect_error = "MySQl connection establish failed.";

if (!mysql_connect($config['mysql_host'], $config['mysql_user'], $config['mysql_pass']) || !mysql_select_db($config['mysql_db'])) {
    die($connect_error);
}
mysql_set_charset("UTF8");

function mysql_current_db() {
    $r = mysql_query("SELECT DATABASE()") or die(mysql_error());
    return mysql_result($r, 0);
}

$query = mysql_query("SELECT * FROM $ubprefix.banlist ORDER BY id") or die("MySQL Error: " . mysql_error());
$sql2 = mysql_query("SELECT * FROM $ubprefix.banlistip") or die("MySQL Error: " . mysql_error());

mysql_select_db($olddb) or die("MySQL Error: " . mysql_error());
while ($data = mysql_fetch_assoc($query)) {
    mysql_select_db($newdb) or die("MySQL Error: " . mysql_error());


    echo "Тип: " . $data['type'] . "<br/>";
    echo "id: " . $data['id'] . "<br/>";

    $name = $data['name'];

    //if(preg_match("/^[\?]+$/", $data['reason'])) {
    $reason = $data['reason'];
    /*} else {
        echo "Record id ".$data['id']." has encoding error. We will change it to 'noReason'.";
        $reason = "NoReason";
    }*/

    $admin = $data['admin'];
    $time = substr($data['time'], 0, 10);
    $temptime = substr($data['temptime'], 0, 10);
    $type = $data['type'];

    if ($type == 0) {
        //ban/tempban
        if ($temptime <= time() && $temptime != 0) {
            //tempban, not expired
            $table = $prefix . "ban_records";
            $sql = "INSERT INTO $table (banned,banned_by,ban_reason,ban_time,ban_expired_on,unbanned_by,unbanned_time)
                    VALUES ('$name','$admin','$reason','$time','$temptime','$admin','$temptime')";
            echo "запрос: " . $sql . "<br/>";
            mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        } else {
            //permaban/tempban, not expired
            $table = $prefix . "bans";
            $sql = "INSERT INTO $table (banned,banned_by,ban_reason,ban_time,ban_expires_on)
                VALUES('$name','$admin','$reason','$time','$temptime')";
            echo "запрос: " . $sql . "<br/>";
            mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        }
    } else if ($type == 1) {
        //ipban
        if ($temptime <= time() && $temptime != 0) {
            $table = $prefix . "ip_records";
            $sql = "INSERT INTO $table (banned,banned_by,ban_reason,ban_time,ban_expired_on,unbanned_by,unbanned_time)
                VALUES('$name','$admin','$reason','$time','$temptime','$admin','$time')";
            echo "запрос: " . $sql . "<br/>";
            mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        } else {
            $table = $prefix . "ip_bans";
            $sql = "INSERT INTO $table (banned,banned_by,ban_reason,ban_time,ban_expires_on)
                VALUES('$name','$admin','$reason','$time','$temptime')";
            echo "запрос: " . $sql . "<br/>";
            mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        }
    } else if ($type == 2) {
        //warn
        $table = $prefix . "warnings";
        $sql = "INSERT INTO $table (warned,warned_by,warn_reason,warn_time) VALUES('$name','$admin','$reason','$time')";
        mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        echo "запрос: " . $sql . "<br/>";
    } else if ($type == 3) {
        //kick
        $table = $prefix . "kicks";
        $sql = "INSERT INTO $table (kicked,kicked_by,kick_reason,kick_time) VALUES('$name','$admin','$reason','$time')";
        mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        echo "запрос: " . $sql . "<br/>";
    } else if ($type == 5) {
        //unban
        $table = $prefix . "ban_records";
        if (strtolower(substr($reason, 0, 10)) == "unbanned:") {
            $reason = substr($reason, 10, strlen($reason)); //Remove "Unbanned: $reason";
        } else if (strtolower(substr($reason, 0, 13)) == "untempbanned:") {
            $reason = substr($reason, 13, strlen($reason)); //Remove "Untempbanned: $reason";
        }
        if ($reason == "") {
            $reason = "NoReason";
        }
        if ($temptime != "0") {
            $sql = "INSERT INTO $table (banned,banned_by,ban_reason,ban_time,ban_expired_on,unbanned_by,unbanned_time)
                    VALUES ('$name','$admin','$reason','$time','$temptime','$admin','$temptime')";
        } else {
            $sql = "INSERT INTO $table (banned,banned_by,ban_reason,ban_time,ban_expired_on,unbanned_by,unbanned_time)
                    VALUES ('$name','$admin','$reason','$time','0','$admin','$time')";
        }
        echo "запрос: " . $sql . "<br/>";
        mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
    } else if ($type == 7) {
        //mute; Ultrabans v.3.* doesn't still support temp mute, so make it expired :)
        $table = $prefix . "mutes_records";
        $sql = "INSERT INTO $table (muted,muted_by,mute_reason,mute_time,mute_expired_on,unmuted_by,unmuted_time)
            VALUES('$name','$admin','$reason','$time','$time','$admin','$time')";
        echo "запрос: " . $sql . "<br/>";
        mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
    } else if ($type == 4) {
        //fine. Ignored
    } else {
        echo "Все плохо!";
        echo "Тип ашипка: " . $data['type'];
    }

}

mysql_select_db($olddb) or die("MySQL Error: " . mysql_error());
while ($data = mysql_fetch_assoc($sql2)) {
    $name = $data['name'];
    $ip = $data['lastip'];
    $sql = "SELECT id FROM cfSessionsUsers WHERE user='$name'";
    mysql_select_db($olddb) or die("MySQL Error: " . mysql_error());
    echo mysql_current_db();
    echo $sql . "<br/>";
    $sql = mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
    if (mysql_num_rows($sql) == 0) {
        $sql = "INSERT INTO cfSessionsUsers (user) VALUES('$name')";
        mysql_select_db($newdb) or die("MySQL Error: " . mysql_error());
        $sql = mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        $uid = mysql_insert_id($sql);
    } else {
        mysql_select_db($olddb) or die("MySQL Error: " . mysql_error());
        $uid = mysql_result($sql, 0);
    }
    $sql = "SELECT time_init FROM cfSessions WHERE uid='$uid' ORDER BY id DESC LIMIT 1";
    echo mysql_current_db();
    echo $sql . "<br/>";
    mysql_select_db($olddb) or die("MySQL Error: " . mysql_error());
    $sql = mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
    if (!mysql_num_rows($sql) == 0) {
        $lastseen = substr(mysql_result($sql, 0), 0, 10);
        mysql_select_db($newdb) or die("MySQL Error: " . mysql_error());
        $ip = ip2long($data['lastip']); //TODO
        $sql = "INSERT INTO bm_player_ips (player,ip,last_seen) VALUES('$name','$ip','$lastseen')";
        echo mysql_current_db();
        echo $sql . "<br/>";
        mysql_query($sql) or die("MySQL Error: " . mysql_error() . ": " . $sql);
        mysql_select_db($olddb) or die("MySQL Error: " . mysql_error());
        echo mysql_current_db() . ": ";
    }
}

echo "<span style='color:red;'>Everything finished ok :)</span>";

?>