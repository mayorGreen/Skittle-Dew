<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);


$venorID =$_GET["vID"];

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT Order_ID, Item_Name FROM Order_Table WHERE Vendor_ID = '?'";
$params = array($venorID);

$stmt = sqlsrv_query($conn, $sql);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

$nameArray = array();

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
