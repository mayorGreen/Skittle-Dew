<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$vendorID = $_COOKIE["vID"];
$orderDate1 = $_POST["date1"];
$orderDate2 = $_POST["date2"];


echo $vendorID;
echo $orderDate1;
echo $orderDate2;

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}


$sql = "SELECT Order_ID FROM Order_Table WHERE Vendor_ID = ? AND Order_Date Between ? AND ? ";
$params = array($vendorID,$orderDate1,$orderDate2);


$stmt = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}


while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $id2 = $row['Order_ID'];
}

$sql2 = "Select * FROM Line_Item_Table WHERE Order_ID = ?";
$params2 = array($id2);

$stmt2 = sqlsrv_query($conn, $sql2, $params2);

if (!$stmt2) {
    die(print_r(sqlsrv_errors(), true));
}


echo "[";

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $tempObj->id = $row['Order_ID'];
    $tempObj->line_item = $row['Line_Item'];
    $tempObj->contract = $row['Contract_Number'];
    $tempObj->name = $row['Item_Name'];
    $tempObj->description = $row['Item_Description'];
    $tempObj->quantity = $row['Item_Quantity'];
    $tempObj->price = $row['Item_Price'];
    $tempObj->total = $row['Total_Price'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);
