<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
$orderID = $_COOKIE['orderID'];
//7 items for orders

$sql = "SELECT User_Login, Order_Date, Order_Complete, Order_Void, Total_Price, Notes FROM Order_Table WHERE Order_ID = ?";
$sql2 = "SELECT * FROM Line_Item_Table WHERE Order_ID = ?";
$params = array($orderID);

$stmt = sqlsrv_query($conn, $sql, $params);
$stmt2 = sqlsrv_query($conn, $sql2, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

echo "[";

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $tempObj->userLogin = $row['User_Login'];
    $tempObj->orderDate = date("Y-m-d", strtotime($row['Order_Date']));
    $tempObj->orderComplete = $row['Order_Complete'];
    $tempObj->orderVoid = $row['Order_Void'];
    $tempObj->totalPrice = number_format($row['Total_Price'],2);
    $tempObj->notes = $row['Notes'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}
while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))
{
    $tempObj->lineItem = $row['Line_Item'];
    $tempObj->contract = $row['Contract_Number'];
    $tempObj->itemName = $row['Item_Name'];
    $tempObj->itemDescription = $row['Item_Description'];
    $tempObj->itemQuantity = $row['Item_Quantity'];
    $tempObj->itemPrice = number_format($row['Item_Price'],2);
    $tempObj->itemTotal = number_format($row['Total_Price'],2);

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);
