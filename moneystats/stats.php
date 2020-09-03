<?php
if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}
require('config.php');
$handle = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die($connect_error);
mysql_select_db($mysql_db, $handle) or die($connect_error);
$sql = mysql_query('SELECT username, balance FROM ' . $mysql_table . ' ORDER BY balance DESC');
$rank = 1;
?>
<center><br>
    <h1>Самые богатые</h1></br></center>
<table width=100% border="1" cellpadding="3" cellspacing="10" style="background-color:#fff;">
    <tr valign="top">
        <td align="center" bgcolor='#FAEBD7'><strong>Место</strong></td>
        <td align="center" bgcolor='#FAEBD7'><strong>Игрок</strong></td>
        <td align="center" bgcolor='#FAEBD7'><strong>Деньги</strong></td>
    </tr>
    <?php
    while ($data = mysql_fetch_assoc($sql)) {
        echo '<tr>';
        if ($data["balance"] > $balance_min) {
            echo '<td align="center"><strong>' . $rank++ . '</strong></td>';
        }
        if ($data["balance"] > $balance_min) {
            echo '<td align="center"><strong>' . $data['username'] . '</strong></td>';
        }
        if ($data["balance"] > $balance_min) {
            echo '<td align="center"><strong>' . $data["balance"] . '</strong></td></tr>';
        }

    }
    ?>
</table>