<?php

require_once("config.php");

$sql = new Sql("software", "host", "database", "user", "password");

$sql->toCSV("QUERY", "filename");

?>
