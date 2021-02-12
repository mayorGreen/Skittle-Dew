<?php

echo('<script type="text/javascript">alert("Order successfully created!");location="tabs.html";</script>');

if (isset($_POST['submit'])) {
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }


    $vendorID = ($_POST[vID]);
    $vendorName = strval($_POST[vName]);
    $userLogin = strval($_POST[uLogin]);
    $countyOffice = strval($_POST[cOffice]);
    $contractNumber = intval($_POST[cNumber]);
    $itemName = strval($_POST[iName]);
    $orderDate = strval($_POST[oDate]);
    echo "$orderDate";
    //represented by a bit (1 or 0) and should be identified as a yes or no drop down.
    //If orderComplete is yes then orderVoid must be no and vice versus
    $orderComplete = boolval($_POST[oComplete]);
    $order_void = boolval($_POST[oVoid]);
    $orderDescription = strval($_POST[oDescription]);
    $orderQuantity = intval($_POST[oQuantity]);
    $unitPrice = doubleval($_POST[uPrice]);
    $totalPrice = doubleval($_POST[tPrice]);
    $notes = strval($_POST[note]);


    if($orderComplete == TRUE) $orderComplete = 1;
    else $orderComplete = 0;

    if($order_void == TRUE) $order_void = 1;
    else $order_void = 0;

    $sql = "SET ANSI_WARNINGS OFF Insert INTO Order_Table(Order_ID,Vendor_ID,Vendor_Name,User_Login,County_Office,Contract_Number
        ,Item_Name,Order_Date,Order_Complete,Order_Void,Order_Description,Order_Quantity,Unit_Price,Total_Price,Notes)
        VALUES(NEWID(),CONVERT(uniqueidentifier,?),?,?,?,?,?,?,?,?,?,?,?,?,?) SET ANSI_WARNINGS ON";
    $params = array($vendorID, $vendorName, $userLogin, $countyOffice, $contractNumber, $itemName, $orderDate, $orderComplete, $order_void,$orderDescription, $orderQuantity, $unitPrice,$totalPrice,$notes);

    $query = sqlsrv_query($conn, $sql, $params);

    if (!$query) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsvr_close($conn);
    echo "We did it!";
}
exit;

