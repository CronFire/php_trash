<?php
if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}
require('config.php');
$handle = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die($connect_error);
mysql_select_db($mysql_db, $handle) or die($connect_error);
$sql = mysql_query('SELECT username, experience, level, job FROM ' . $mysql_table . ' ORDER BY level DESC LIMIT 10');
$rank = 1;
?>
<center><br>
    <h1>Профессии</h1><br></center>
<div class="stats">
    <table width=100% cellspacing="0" class="bordered" style="background-color:#fff;">
        <tr>
            <th align="center" bgcolor='#FAEBD7'>Место</th>
            <th align="center" bgcolor='#FAEBD7'>Игрок</th>
            <th align="center" bgcolor='#FAEBD7'>Уровень</th>
            <th align="center" bgcolor='#FAEBD7'>Опыт</th>
            <th align="center" bgcolor='#FAEBD7'>Работа</th>
        </tr>
        <?php
        while ($data = mysql_fetch_assoc($sql)) {
            echo '<tr>';
            if ($data["level"] > $lvl_min) {
                echo '<td align="center">' . $rank++ . '</td>';
            }
            if ($data["level"] > $lvl_min) {
                echo '<td align="center">' . $data['username'] . '</td>';
            }
            if ($data["level"] > $lvl_min) {
                echo '<td align="center">' . $data["level"] . '</td>';
            }
            if ($data["level"] > $lvl_min) {
                echo '<td align="center">' . $data["experience"] . '</td>';
            }
            if ($data['job'] == "Miner") {
                if ($data["level"] > $lvl_min) {
                    echo "<td align='center'>Шахтер</td>";
                }
            } else {
                if ($data['job'] == "Woodcutter") {
                    if ($data["level"] > $lvl_min) {
                        echo "<td align='center'>Лесоруб</td>";
                    }
                } else {
                    if ($data['job'] == "Hunter") {
                        if ($data["level"] > $lvl_min) {
                            echo "<td align='center'>Охотник</td>";
                        }
                    } else {
                        if ($data['job'] == "Fisherman") {
                            if ($data["level"] > $lvl_min) {
                                echo "<td align='center'>Рыболов</td>";
                            }
                        } else {
                            if ($data['job'] == "Digger") {
                                if ($data["level"] > $lvl_min) {
                                    echo "<td align='center'>Копатель</td>";
                                }
                            } else {
                                if ($data['job'] == "Builder") {
                                    if ($data["level"] > $lvl_min) {
                                        echo "<td align='center'>Строитель</td>";
                                    }
                                } else {
                                    if ($data['job'] == "Farmer") {
                                        if ($data["level"] > $lvl_min) {
                                            echo "<td align='center'>Фермер</td>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        ?>
        </tr></table>
</div>