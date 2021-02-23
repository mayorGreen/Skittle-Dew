<?php

echo('<script type="text/javascript">alert("Order successfully created!");location="../Front End/public_html/tabs.html";</script>');

if (isset($_POST['submit']))
{
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }


    //Variables for Order_Table
    $vendorID = ($_POST[vID]);
    $userLogin = strval($_POST[uLogin]);
    $orderName = strval($_POST[oName]);
    $orderDate1 = strval($_POST[oDate]);
    $orderDate = str_replace('T',' ',$orderDate1);

    //represented by a bit (1 or 0) and should be identified as a yes or no drop down.
    //If orderComplete is yes then orderVoid must be no and vice versus
    $orderComplete = boolval($_POST[oComplete]);
    $order_void = boolval($_POST[oVoid]);
    $totalPrice = doubleval($_POST[tprice]);
    if($orderComplete == TRUE) $orderComplete = 1;
    else $orderComplete = 0;

    if($order_void == TRUE) $order_void = 1;
    else $order_void = 0;


    //Sql for Order_Table
    $sql1 = "SET ANSI_WARNINGS OFF Insert INTO Order_Table(Order_ID,Vendor_ID,User_Login,Order_Name,
        Order_Date,Order_Complete,Order_Void,Total_Price)
        VALUES(NEWID(),CONVERT(uniqueidentifier,?),?,?,?,?,?,?) SET ANSI_WARNINGS ON";
    $params1 = array($vendorID,$userLogin,$orderName,$orderDate,$orderComplete,$order_void,$totalPrice);

    $query1 = sqlsrv_query($conn, $sql1, $params1);

    if (!$query1) {
        die(print_r(sqlsrv_errors(), true));
    }



    //Select Order_ID from Order_Table
    $sql3 = "Select * From Order_Table where Order_Date = ?";
    $param = array($orderDate);
    $query = sqlsrv_query($conn,$sql3, $param);

    $id = '';

    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
        $id = $row['Order_ID'];
    }

    //Code to iterate through item array and assign them to variables to submit to line item table
    $j = 0;

    for($i = 0;$i<count($_POST['items'])/6;$i++){

        //Variables for Line_Item_Table
        $itemName = strval($_POST[items][$j]);
        $j++;
        $orderDescription = strval($_POST[items][$j]);
        $j++;
        $orderQuantity = intval($_POST[items][$j]);
        $j++;
        $unitPrice = doubleval($_POST[items][$j]);
        $j++;
        $totalPrice = doubleval($_POST[items][$j]);
        $j++;
        $notes = strval($_POST[items][$j]);
        $j++;


        //SQL for Line_Item_Table
        $sql2 = "SET ANSI_WARNINGS OFF Insert INTO Line_Item_Table(Order_ID,Item_Name,
        Item_Description,Item_Quantity,Item_Price,Total_Price,Notes)
        VALUES(CONVERT(uniqueidentifier,'$id'),'".$itemName."','".$orderDescription."','".$orderQuantity."','".$unitPrice."','".$totalPrice."','".$notes."') SET ANSI_WARNINGS ON";

        $query2 = sqlsrv_query($conn, $sql2);

        if (!$query2) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    sqlsvr_close($conn);
}
exit;

