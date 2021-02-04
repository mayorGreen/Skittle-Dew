<?php

echo('<script type="text/javascript">alert("Order successfully created!");location="http://localhost/Buch_County/index.html";</script>');

if (isset($_POST['submit'])) {
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }


    $vendorID = strval($_POST[vID]);
    $vendorName = strval($_POST[vName]);
    $userLogin = strval($_POST[uLogin]);
    $countyOffice = strval($_POST[cOffice]);
    $contractNumber = intval($_POST[cNumber]);
    $itemName = strval($_POST[iName]);
    $orderDate = strval($_POST[oDate]);
    //represented by a bit (1 or 0) and should be identified as a yes or no drop down.
    //If orderComplete is yes then orderVoid must be no and vice versus
    $orderComplete = intval($_POST[oComplete]);
    $order_void = intval($_POST[oVoid]);
    $orderDescription = strval($_POST[oDescription]);
    $orderQuantity = intval($_POST[oQuantity]);
    $unitPrice = intval($_POST[uPrice]);
    //Item total is unneeded and has been removed from the database
    //Rob you will need to remove this from your database as well
    //$itemTotal = intval($_POST[iTotal]);
    $totalPrice = intval($_POST[tPrice]);
    $notes = strval($_POST[note]);

    $sql = "SET ANSI_WARNINGS OFF SET IDENTITY_INSERT Order_Table ON Insert INTO Order_Table(Order_ID,Vendor_ID,Vendor_Name,User_Login,County_Office,Contract_Number
        ,Item_Name,Order_Date,Order_Complete,Order_Void,Order_Description,Order_Quantity,Unit_Price,Item_Total,Total_Price,Notes) 
        VALUES(NEWID(),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) SET ANSI_WARNINGS ON";
    $params = array($vendorID, $vendorName, $userLogin, $countyOffice, $contractNumber, $itemName, $orderDate, $orderComplete, $order_void,$orderDescription, $orderQuantity, $unitPrice,$itemTotal,$totalPrice,$notes);

    $query = sqlsrv_query($conn, $sql, $params);

    if (!$query) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsvr_close($conn);
}
exit;

