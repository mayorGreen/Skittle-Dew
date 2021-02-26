<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);


if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$vendorID = $_COOKIE["vID"];
$orderDate1 = $_COOKIE["date1"];
$orderDate2 = $_COOKIE["date2"];


$sql = "SELECT Order_ID, Total_Price, Order_Complete, Order_Void FROM Order_Table WHERE Vendor_ID = ? AND Order_Date Between ? AND ? ";
$params = array($vendorID,$orderDate1,$orderDate2);

//echo var_dump($params);

$stmt = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

/*
 * We are querying the database for the order total price, and the order void and complete status.
 * We may not need these and may just wish to total the entirety of the orders made by with the vendor within the date period.
 * If we do not need these they can be taken out or left in in case we decide to use them later.
 *
 */
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $id2 = $row['Order_ID'];
    $orderPrice = $row['Total_Price'];
    $orderComplete = $row['Order_Complete'];
    $orderVoid = $row['Order_Void'];
}


//This elif statement will return yes and no values for the binary values of order void or complete. We should change these to yes and no so that the client and end user can easily tell
//if an order has been completed or not

if($orderComplete == 1)
{
    $complete = 'Yes';
    $void = 'No';

} elseif ($orderVoid == 1){ $complete = 'No'; $void = 'Yes';}
else {$complete = 'No'; $void = 'No';}


//Uneeded echo statemement just testing if all the data was being filled.
//echo "The order ID is ".$id2. " the total order cost is: $".$orderPrice." order complete: ".$complete." order void: ".$void." **********";


$sql2 = "Select * FROM Line_Item_Table WHERE Order_ID = ?";
$params2 = array($id2);

$stmt2 = sqlsrv_query($conn, $sql2, $params2);

if (!$stmt2) {
    die(print_r(sqlsrv_errors(), true));
}


echo "[";

/*
 * This line below takes all of the data from the line_item_table query and echos it in json for so that you can use it for the report
 */

while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))
{
    $tempObj->id = $row['Order_ID'];
    $tempObj->line_item = $row['Line_Item'];
    $tempObj->contract = $row['Contract_Number'];
    $tempObj->name = $row['Item_Name'];
    $tempObj->description = $row['Item_Description'];
    $tempObj->quantity = $row['Item_Quantity'];
    $tempObj->itemPrice = $row['Item_Price'];
    $tempObj->itemTotal = $row['Total_Price'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);
