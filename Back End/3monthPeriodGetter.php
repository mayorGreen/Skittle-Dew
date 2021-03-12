<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

//defines the vendor id and the current date then subtracts 3 months from the current date to compare against current orders
$vendorID = $_COOKIE["vID"];
$currentDate = date('Y-m-d');
$currentDate = strtotime('-3 months', strtotime($currentDate));

$sql = "SELECT Order_Date, Total_Price FROM Order_Table WHERE Vendor_ID = ?";
$params = array($vendorID);
$stmt = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}


echo "[";

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $date = $row['Order_Date'];
    $date = $date->format('Y-m-d');
    $tempObj->date = $date;
    $tempObj->total = $row['Total_Price'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);