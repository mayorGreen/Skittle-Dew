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
    $orderID =uniqid(); // used to create a unique id to query order table to insert line items
    $vendorID = ($_POST[vID]);
    $userLogin = strval($_POST[uLogin]);
    $orderDate1 = strval($_POST[oDate]);
    $orderDate = str_replace('T',' ',$orderDate1);

    //represented by a bit (1 or 0) and should be identified as a yes or no drop down.
    //If orderComplete is yes then orderVoid must be no and vice versus
    $orderComplete = 0;
    $order_void = 0;
    $totalPrice = doubleval($_POST[totalCost]);
    $notes = strval($_POST[note]);
    $email = strval($_POST[enterEmail]);

    //code to hopefully send an email after an order has been placed
    /*
    $to = $email; // this is your Email address
    $from = "jtrickel@missouriwestern.edu"; // this is the sender's Email address
    $subject = "Order Confirmation";
    $message = "Thank you ".$userLogin." for your order. \n Order created on: ".$orderDate."\n Order Total: $".$totalPrice;

    $headers = "From:" . $from;
    $headers2 = "From:" . $to;
    mail($to,$subject,$message,$headers);
    */

    /*
    if($orderComplete == TRUE) $orderComplete = 1;
    else $orderComplete = 0;

    if($order_void == TRUE) $order_void = 1;
    else $order_void = 0;
    */


    //Sql for Order_Table
    $sql1 = "SET ANSI_WARNINGS OFF Insert INTO Order_Table(Order_ID,Vendor_ID,User_Login,
        Order_Date,Order_Complete,Order_Void,Total_Price,Notes)
        VALUES(?,CONVERT(uniqueidentifier,?),?,?,?,?,?,?) SET ANSI_WARNINGS ON";
    $params1 = array($orderID,$vendorID,$userLogin,$orderDate,$orderComplete,$order_void,$totalPrice,$notes);

    $query1 = sqlsrv_query($conn, $sql1, $params1);

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

    //Code to iterate through item array and assign them to variables to submit to line item table
    $j = 0;

    for($i = 0;$i<count($_POST['items'])/6;$i++){

        //Variables for Line_Item_Table
        $contract_Number = intval($_POST[items][$j]);
        $j++;
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

        //SQL for Line_Item_Table
        $sql2 = "SET ANSI_WARNINGS OFF Insert INTO Line_Item_Table(Order_ID,Contract_Number,Item_Name,
        Item_Description,Item_Quantity,Item_Price,Total_Price)
        VALUES('$id','".$contract_Number."','".$itemName."','".$orderDescription."','".$orderQuantity."','".$unitPrice."','".$totalPrice."') SET ANSI_WARNINGS ON";

        $query2 = sqlsrv_query($conn, $sql2);

        if (!$query2) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    sqlsvr_close($conn);
}
exit;

