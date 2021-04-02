<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$order_id = strval($_GET[oID]);

$sql = "SELECT * FROM Line_Item_Table WHERE Order_ID = '?'";
$params = array($order_id);

$stmt = sqlsrv_query($conn, $sql,$params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

