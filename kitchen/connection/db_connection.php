<?php

$sname = "sql12.freesqldatabase.com";
$unmae = "sql12743670";
$password = "9cW8XygX9H";

$db_name = "sql12743670";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
	echo "Connection failed!";
}
