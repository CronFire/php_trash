<?php
/*
 * COPYRIGHT (c) 2013 Zeluboba (Roman Zabaluev)
 * This file is part of NicksBanFix
 * Date: 23.03.14
 * Time: 17:49
 * DO NOT DISTRIBUTE.
 */
define('_CFEXEC', true);

require('/var/www/engine/modules/server/support/config.php');


mysql_select_db("cfMC_BanManager");

$query1 = mysql_query("SELECT * FROM `bm_bans`") or die("query fail" . mysql_error());
$query2 = mysql_query("SELECT * FROM `bm_ban_records`") or die("query fail" . mysql_error());
$query3 = mysql_query("SELECT * FROM `bm_ip_bans`") or die("query fail" . mysql_error());
$query4 = mysql_query("SELECT * FROM `bm_ip_records`") or die("query fail" . mysql_error());
$query5 = mysql_query("SELECT * FROM `bm_kicks`") or die("query fail" . mysql_error());
$query6 = mysql_query("SELECT * FROM `bm_mutes`") or die("query fail" . mysql_error());
$query7 = mysql_query("SELECT * FROM `bm_mutes_records`") or die("query fail" . mysql_error());
$query8 = mysql_query("SELECT * FROM `bm_player_ips`") or die("query fail" . mysql_error());
$query9 = mysql_query("SELECT * FROM `bm_warnings`") or die("query fail" . mysql_error());

function selectUsers() {
    mysql_select_db("cronfirewww");
}

function selectMain() {
    mysql_select_db("cfMC_BanManager");
}

selectMain();
while ($data = mysql_fetch_assoc($query1)) {
    $currentTable = 'bm_bans';
    $nick = $data['banned'];
    $id = $data['ban_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `banned` = '$sensNick' WHERE `ban_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query2)) {
    $currentTable = 'bm_ban_records';
    $nick = $data['banned'];
    $id = $data['ban_record_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `banned` = '$sensNick' WHERE `ban_record_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query3)) {
    $currentTable = 'bm_ip_bans';
    $nick = $data['banned'];
    $id = $data['ban_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `banned` = '$sensNick' WHERE `ban_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query4)) {
    $currentTable = 'bm_ip_records';
    $nick = $data['banned'];
    $id = $data['ban_record_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `banned` = '$sensNick' WHERE `ban_record_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query5)) {
    $currentTable = 'bm_kicks';
    $nick = $data['kicked'];
    $id = $data['kick_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `kicked` = '$sensNick'  WHERE `kick_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query6)) {
    $currentTable = 'bm_mutes';
    $nick = $data['muted'];
    $id = $data['mute_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `muted` = '$sensNick' WHERE `mute_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query7)) {
    $currentTable = 'bm_mutes_records';
    $nick = $data['muted'];
    $id = $data['mute_record_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `muted` = '$sensNick' WHERE `mute_record_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query8)) {
    $currentTable = 'bm_player_ips';
    $nick = $data['player'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `player` = '$sensNick' WHERE `player` = '$nick'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

selectMain();
while ($data = mysql_fetch_assoc($query9)) {
    $currentTable = 'bm_warnings';
    $nick = $data['warned'];
    $id = $data['warn_id'];
    selectUsers();
    $query = mysql_query("SELECT `name` FROM `dle_users` WHERE LOWER(`name`) = LOWER('$nick')") or die("query fail" . mysql_error());
    if (mysql_num_rows($query) == 1) {
        $sensNick = mysql_result($query, 0) or die("query fail" . mysql_error());
        if ($nick !== $sensNick) {
            selectMain();
            mysql_query("UPDATE $currentTable SET `warned` = '$sensNick' WHERE `warn_id` = '$id'") or die("query fail" . mysql_error());
            echo "Converted " . $nick . " -> " . $sensNick . " in table " . $currentTable . "<br/>";
        }
    }
}

echo 'Finished.';
