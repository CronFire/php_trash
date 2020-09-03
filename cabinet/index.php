<?php
if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}
error_reporting(0);
$def_charset = "UTF-8";
include('config.php');
if ($_POST) {
    if ($username == '') {
        $mes = "Сначала авторизуйтесь!";
    } else {
        $imageinfo = getimagesize($_FILES['userfile']['tmp_name']);
        if ($_FILES['userfile']['type'] == "image/png") {
            if ($imageinfo['mime'] == 'image/png') {
                if ($imageinfo["0"] == '64') {
                    if ($imageinfo["1"] == '32') {
                        if (preg_match("/\.(png)$/i", $_FILES['userfile']['name'])) {
                            if (is_uploaded_file($_FILES["userfile"]["tmp_name"])) {
                                if ($_POST["Mod"] == "1") {
                                    move_uploaded_file($_FILES["userfile"]["tmp_name"], $dir_skins . $username . ".png");
                                    $mes = "Скин успешно обновлен";
                                } else if ($_POST["Mod"] == "2") {
                                    move_uploaded_file($_FILES["userfile"]["tmp_name"], $dir_cloaks . $username . ".png");
                                    $mes = "Плащ успешно обновлен";
                                }
                            } else {
                                $mes = "Ошибка записи файла!";
                            }
                        } else {
                            $mes = "Ошибка в имени файла!";
                        }
                    } else {
                        $mes = "Слишком высокий файл!";
                    }
                } else {
                    $mes = "Слишком широкий файл!";
                }
            } else {
                $mes = "Неверный тип файла!";
            }
        } else {
            $mes = "Неверный тип файла!";
        }
    }
} else {
    if ($mes != "" && isset($mes)) {
        echo '<font color="red">';
        echo $mes;
        echo '</font>';
        echo '<br>';
        echo '<br>';
    }
}

echo '<h1> Скин:</h1><br />';
if (!file_exists($dir_skins . $username . '.png')) {
    $skinpath = $dir_skins . 'default.png';
} else {
    $skinpath = $dir_skins . $username . '.png';
}
echo '<img src="/' . $dir_main . 'skin2d.php?skinpath=http://' . $url . '/' . $skinpath . '" /><br><br><br><br><hr>';
echo '<h1>Плащ:</h1>';


if (!file_exists($dir_cloaks . $username . '.png')) {
    echo 'Плаща нет.<br /><br />';
} else {
    echo '<form action="" method="post"><input type="submit" name="delete" value="Удалить" class="button" /><br /><br /></form>';
    echo '<img src="/' . $dir_main . 'cloak2d.php?skinpath=http://' . $url . '/' . $skinpath . '" /><hr>';
}

?>

<font color="red"><b>Правила загрузки:</b></font>
<div>
    <span style="color: rgb(0, 0, 205); font-weight: bold; ">Скин:</span>
</div>
<div>1.Размер - 64x32</div>
<div>2.Формат - .png</div>
<div>
    <span style="color: rgb(0, 0, 205); font-weight: bold; ">Плащ:</span>
</div>
<div>1.Размер 1024x512</div>
<div>2.Формат - .png</div>

<form action="" method="post" enctype="multipart/form-data">
    <br/><input type="file" name="userfile"/>
    <br/>
    <input type="submit" value="Загрузить" name="upload_submit" class="button"/><br/><br/>
    </p>
</form>