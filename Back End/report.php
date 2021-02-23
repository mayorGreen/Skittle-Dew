<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$order_id = $_COOKIE[oID];

$sql = "SELECT * FROM Line_Item_Table WHERE Order_ID = ?";
$params = array($order_id);

$stmt = sqlsrv_query($conn, $sql,$params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

echo "[";

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $tempObj->id = $row['Order_ID'];
    $tempObj->item = $row['Line_Item'];
    $tempObj ->name = $row['Item_Name'];
    $tempObj->quantity = $row['Item_Quantity'];
    $tempObj->price = $row['Item_Price'];
    $tempObj ->total = $row['Total_Price'];
    $tempObj ->notes = $row['Notes'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);

