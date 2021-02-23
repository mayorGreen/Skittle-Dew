<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);


$venorID = $_COOKIE["vID"];


if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}


$sql = "SELECT Order_ID, Order_Name FROM Order_Table WHERE Vendor_ID = ?";
$params = array($venorID);


$stmt = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

echo "[";

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $tempObj->id = $row['Order_ID'];
    $tempObj->name = $row['Order_Name'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);
