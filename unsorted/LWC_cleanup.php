<?php

mysql_connect('cronfire.ru', 'tester', 'tester') or die('connect error');
mysql_select_db('cronfiremc') or die('db select error');

//world_test, world_main, world_event, world_the_end, world_halloween, world_event3, world_event2, world_christams, 

$array = array('world_test', 'world_main', 'world_event', 'world_the_end', 'world_halloween', 'world_event3', 'world_event2', 'world_christmas', 'world_testp');

foreach ($array as $wrld) {

    $sql = mysql_query("SELECT * FROM `lwc_protections` WHERE `world` = '$wrld' ") or die('ololo');

    while ($data = mysql_fetch_assoc($sql)) {
        $id = $data['id'];
        $PR = array();
        $PR[] = $id;
        echo '<br>';
//echo $data['world'];
//var_dump($PR);

        mysql_query("DELETE FROM `lwc_history` WHERE `protectionId` = '$id'") or die('ololo');
        mysql_query("DELETE FROM `lwc_protections` WHERE `id` = '$id'") or die('ololo');
    }

//mysql_query("DELETE FROM lwc_protections WHERE world ");

}

?>