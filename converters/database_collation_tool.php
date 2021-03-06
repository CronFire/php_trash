﻿<?PHP
/*
 * @tool	Phoca Changing Collation
 * @Changing collation of database, tables and columns
 * @Run this script only at your own risk. If you have a big database
 * @you need to change the script execution time in your php
 * @copyright (C) Jan Pavelka www.phoca.cz (http://www.phoca.cz)
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @based on script from http://php.vrana.cz/ - Author - Jakub Vrana
 * @license http://creativecommons.org/licenses/by/2.5/
 * @Creative Commons Attribution 2.5 Generic
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="index, follow" />
  <meta name="keywords" content="phoca, server unzip" />
  <meta name="description" content="Phoca Server Unzip tool" />
  <meta name="generator" content="www.phoca.cz" />
  <title>Phoca Server Unzip tool</title>
  <style type="text/css">
body {font-family: Arial, sans-serif; font-size: 10px; color: #000000 ;}
h1 a {color:#006699;text-decoration:none;}
#info {position: relative;float:right; top:10px; right:10px; text-align:right;margin-bottom:10px;}
.error {font-weight:bold;color:#c10000}
.warning {font-weight:bold;color:#ff8102}
.success {font-weight:bold;color:#008040}
.window {position:relative;top:10px;left:10px;width:95%;padding:5px;height:300px;overflow:auto;border:1px solid #000;background:#fbfbfb;clear:both;}
</style>
</head>
<body>
<div id="info">
	<img src="assets/phoca-logo.png" alt="Phoca" /><br />
	<a href="http://www.phoca.cz/">www.phoca.cz</a><br />
	<a href="http://www.phoca.cz/forum">www.phoca.cz/forum</a>
</div>

<h1><a href="index.php">Phoca Changing Collation tool</a></h1>
<?php
function start_db($mysqlhost,$mysqldatabase, $mysqluser, $mysqlpass)
{
	global $conn;
	$conn = mysql_connect($mysqlhost, $mysqluser, $mysqlpass);
    if (!$conn)
	{
		echo '<a href="index.php" class="back">Back to the main site</a><br />';
		die('Database error.');
	}	
	$select = mysql_select_db($mysqldatabase, $conn);
    if (!$select)
    {
		echo '<a href="index.php" class="back" >Back to the main site</a><br />';
		die('Database error.');
	}
}
function end_db ($conn)
{
	mysql_close($conn);
}

if (   isset($_POST['host'])
	&& isset($_POST['user'])
	&& isset($_POST['pass'])
	&& isset($_POST['name'])
	&& isset($_POST['col']))
{
	$mysqlhost 		= $_POST['host'];
	$mysqluser 		= $_POST['user'];
	$mysqlpass 		= $_POST['pass'];
	$mysqldatabase 	= $_POST['name'];
	$collation 		= $_POST['col'];
	
	
	// Change the time -------------------------------------
	$changedMaxExecTime		= 0;
	$standardMaxExecTime 	= ini_get('max_execution_time');
	if ($standardMaxExecTime < 120) {
		set_time_limit(120);
		$changedMaxExecTime	= 1;
	}
	// -----------------------------------------------------
	
	
	start_db($mysqlhost,$mysqldatabase, $mysqluser, $mysqlpass);

	 //Start code from http://php.vrana.cz/ - Author - Jakub Vrana
	function mysql_convert($query) {
		echo '<div>' . $query . ' ... <span style="color:#26d92b;">OK</span></div>';
	    return mysql_query($query);
	}
	
	echo '<div></div>';
	echo '<div class="window">';
	mysql_convert("ALTER DATABASE $mysqldatabase COLLATE $collation");
	
	$result = mysql_query("SHOW TABLES");
	while ($row = mysql_fetch_row($result)) {
	    mysql_convert("ALTER TABLE $row[0] COLLATE $collation");
	    $result1 = mysql_query("SHOW COLUMNS FROM $row[0]");
	    while ($row1 = mysql_fetch_assoc($result1)) {
	        if (preg_match('~char|text|enum|set~', $row1["Type"])) {
	            mysql_convert("ALTER TABLE $row[0] MODIFY $row1[Field] $row1[Type] CHARACTER SET binary");
	            mysql_convert("ALTER TABLE $row[0] MODIFY $row1[Field] $row1[Type] COLLATE $collation" . ($row1["Null"] ? "" : " NOT NULL") . ($row1["Default"] && $row1["Default"] != "NULL" ? " DEFAULT '$row1[Default]'" : ""));
	        }
	    }
	}
	echo '</div>';
	
	mysql_free_result($result);
	//End code from http://php.vrana.cz/ - Author - Jakub Vrana
	end_db($conn);
	echo '<p>&nbsp;</p><a href="index.php" class="back">Back to the main page</a>';
	
	
	// Set back the time --------------------
	if ($changedMaxExecTime == 1) {
		set_time_limit($standardMaxExecTime);
	}
	// --------------------------------------
}
else
{
?>
	<h2>Change database collation (DATABASE, TABLES, COLUMNS)</h2>
	<form action="index.php" method="post">
	<table>
		<tr><td>Database Host</td><td><input type="text" name="host" value="localhost" /></td></tr>
	<tr><td>Database User</td><td><input type="text" name="user" value="username" /></td></tr>
	<tr><td>Database Password</td><td><input type="password" name="pass" value="password" /></td></tr>
	<tr><td>Database Name</td><td><input type="text" name="name" value="database name" /></td></tr>
	<tr><td>Database Collation</td><td><input type="text" name="col" value="utf8_general_ci" /></td></tr>
	<tr><td></td><td><input type="submit" value="Submit" /></td></tr>
	</table>
	</form>
<?php
}
?>
</body>
</html>