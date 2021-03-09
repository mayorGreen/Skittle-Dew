<?php

echo('<script type="text/javascript">alert("Order successfully updated!");location="../Front End/public_html/tabs.html";</script>');

if (isset($_POST['submit']))
{
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }


    //Variables for Order_Table
    $orderID = $_COOKIE[orderID];

    //represented by a bit (1 or 0) and should be identified as a yes or no drop down.
    //If orderComplete is yes then orderVoid must be no and vice versus
    $orderComplete = boolval($_POST[eoComplete]);
    $order_void = boolval($_POST[eoVoid]);
    $totalPrice = doubleval($_POST[etotalCost]);
    $notes = strval($_POST[enote]);

    if($orderComplete == TRUE) $orderComplete = 1;
    else $orderComplete = 0;

    if($order_void == TRUE) $order_void = 1;
    else $order_void = 0;

    //Sql for Order_Table
    $sql = "UPDATE Order_Table SET Order_Complete = '$orderComplete', Order_Void = '$order_void',
            Total_Price = '$totalPrice', Notes = '$notes' Where Order_ID = ?";
    $params = array($orderID);

    $query1 = sqlsrv_query($conn, $sql, $params);

    if (!$query1) {
        die(print_r(sqlsrv_errors(), true));
    }


    //Select Order_ID from Order_Table
    $sql3 = "Select * From Order_Table where Order_ID = ?";
    $param = array($orderID);
    $query = sqlsrv_query($conn,$sql3, $param);

    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
        $id = $row['Order_ID'];
    }

    //Select items from Line_Item_Table
    $sql4 = "Select * From Line_Item_Table where Order_ID = ?";
    $param = array($orderID);
    $query3 = sqlsrv_query($conn,$sql4, $param);
    $rowCount = 0;

    while($row = sqlsrv_fetch_array($query3, SQLSRV_FETCH_ASSOC))
    {
        $rowCount ++;
    }


    //Code to iterate through item array and assign them to variables to submit to line item table
    $j = 0;
    var_dump($_POST['eitems']);

    for($i = 0;$i<count($_POST['eitems'])/7;$i++){

        //Variables for Line_Item_Table
        $lineItem = intval($_POST[eitems][$j]);
        echo $lineItem;
        $j++;
        $contract_Number = intval($_POST[eitems][$j]);
        $j++;
        $itemName = strval($_POST[eitems][$j]);
        $j++;
        $orderDescription = strval($_POST[eitems][$j]);
        $j++;
        $orderQuantity = intval($_POST[eitems][$j]);
        $j++;
        $unitPrice = doubleval($_POST[eitems][$j]);
        $j++;
        $totalPrice = doubleval($_POST[eitems][$j]);
        $j++;

        //SQL for Line_Item_Table
        $sql2 = "UPDATE Line_Item_Table SET Contract_Number = ?,Item_Name = ?,
        Item_Description = ?,Item_Quantity = ?,Item_Price = ? ,Total_Price = ?
        WHERE Order_ID = '$orderID' AND Line_Item = ?";
        $params2 = array($contract_Number, $itemName,$orderDescription,$orderQuantity,$unitPrice,$totalPrice,$lineItem);

        $query2 = sqlsrv_query($conn, $sql2,$params2);

        if (!$query2) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    sqlsvr_close($conn);
}
exit;