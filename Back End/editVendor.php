<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
$vendorID = $_COOKIE['vendorID'];
//7 items for orders

$sql = "SELECT Vendor_Name, Vendor_Address, Vendor_City, Vendor_State, Vendor_Zip_Code, Vendor_Phone, Vendor_Email FROM Vendor_Table WHERE Vendor_ID = ?";

$params = array($vendorID);

$stmt = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

echo "[";

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $tempObj->vendorName = $row['Vendor_Name'];
    $tempObj->vendorAddress = $row['Vendor_Address'];
    $tempObj->vendorCity = $row['Vendor_City'];
    $tempObj->vendorState = $row['Vendor_State'];
    $tempObj->vendorZip = $row['Vendor_Zip_Code'];
    $tempObj->vendorPhone = $row['Vendor_Phone'];
    $tempObj->vendorEmail = $row['Vendor_Email'];

    $myJSON = json_encode($tempObj);
    echo $myJSON;
    echo ",";
}

echo $myJSON;
echo "]";

sqlsrv_free_stmt($stmt);
