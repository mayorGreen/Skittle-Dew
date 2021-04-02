<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);


$venorID = $_COOKIE["vID"];
echo $venorID;

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

echo "        ";

$sql = "SELECT Order_ID, Order_Date FROM Order_Table WHERE Vendor_ID = '?'";
$params = array($venorID);

echo ",,,,,,,,,,,,,,,,,,,,,,,,,";

$stmt = sqlsrv_query($conn, $sql, $params);
var_dump($stmt);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

echo "[";

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    //echo json_encode($row['Vendor_ID']);
    //echo json_encode($row['Vendor_Name']);
    $tempObj->id = $row['Order_ID'];
    $tempObj->name = $row['Item_Name'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);
