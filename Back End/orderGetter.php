<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);


if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
//Variables from user input
$contract = $_COOKIE["conSelect"];
$vendorID = $_COOKIE["vID"];
$vendorName = $_COOKIE["vName"];
//1 day subracted from "from date" and added to "to date" so that "Between" in query works properly
$orderDate1 = date("Y-m-d", strtotime("-1 day", strtotime($_COOKIE["date1"])));
$orderDate2 = date("Y-m-d", strtotime("+1 day", strtotime($_COOKIE["date2"])));
$vendorTotal = 0.0;

//SQL for all orders
$sql = "SELECT Order_ID FROM Order_Table WHERE Vendor_ID = ? AND Order_Date Between ? AND ? ";
$params = array($vendorID,$orderDate1,$orderDate2);

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
//SQL for contract orders
$sql1 = "Select * FROM Line_Item_Table WHERE Order_ID in (?";

//SQL for non-contract orders
$sql3 = "Select * FROM Line_Item_Table WHERE Order_ID in (?";

//SQL for all orders
$sql2 = "Select * FROM Line_Item_Table WHERE Order_ID in (?";


$a = ",?";
$params2 = array();
$numRows = 0;

//Appends order ids to params2 and adds an extra variable to each sql statement
//very sloppy usage, but it works
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $id2 = $row['Order_ID'];
    array_push($params2, $id2);
    $numRows ++;
}
for($i = 1; $i <$numRows; $i++)
{
    //SQL for contract orders
    $sql1 .= $a;

    //SQL for non-contract orders
    $sql3 .= $a;

    //SQL for all orders
    $sql2 .= $a;
}

//Returns reports based on whether an item has a contract or not
if(strcmp($contract, "Contract") == 0)
{
    $sql1 .=") AND Contract_Number != 0";
    $stmt2 = sqlsrv_query($conn, $sql1, $params2);
}elseif(strcmp($contract,"Non-Contract") == 0)
{
    $sql3 .= ") AND Contract_Number = 0";
    $stmt2 = sqlsrv_query($conn, $sql3, $params2);
}else
{
    $sql2 .= ")";
    $stmt2 = sqlsrv_query($conn, $sql2, $params2);
}

if (!$stmt2) {
    die(print_r(sqlsrv_errors(), true));
}

//reverts dates back to user selected dates
$orderDate1 = date('F d, Y', strtotime("+1 day", strtotime($orderDate1)));
$orderDate2 = date('F d, Y', strtotime("-1 day", strtotime($orderDate2)));


$html1 = "<html> 
    <head> 
        <link rel='icon' href='../Front%20End/public_html/favicon.ico'>
        <link rel='stylesheet' href='../Front%20End/public_html/style.css'>
        <title>PO System</title>
        <style>
            table {font-family: arial, sans-serif; border-collapse: collapse;width: 75%;}
            td, th { border: 1px solid #dddddd; text-align: center; padding: 4px;}
            tr:nth-child() {background-color: #dddddd;}
        </style>
    </head>
<body>
<div id='info'>
    <h5 align='center'>
        <img src='../Front%20End/public_html/favicon.ico'>
    </h5>
    <h2 align='center'>$vendorName Purchase Report</h2>
    <h3 align='center'>For orders between: $orderDate1 and $orderDate2 </h3>
</div>
<div id='tab'>
<table align='center'>
  <tr>
    <th>Line Item</th>
    <th>Contract</th>
    <th>Item Name</th>
    <th>Item Description</th>
    <th>Qty</th>
    <th>Unit Price</th>
    <th>Total Price</th>
  </tr>";

echo $html1;
/*
 * This line below takes all of the data from the line_item_table query and echos it in json for so that you can use it for the report
 */
$i=0;
while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))
{
    $id = $row['Order_ID'];
    $line_item = $row['Line_Item'];
    $contract = $row['Contract_Number'];
    $name = $row['Item_Name'];
    $description = $row['Item_Description'];
    $quantity = $row['Item_Quantity'];
    $itemPrice = $row['Item_Price'];
    $itemTotal = $row['Total_Price'];

    if($contract == 0)
    {
        $contract = "Non-Contract";
    }

    $vendorTotal += $itemTotal;

    echo "<tr><td>$line_item</td>";
    echo "<td>$contract</td>";
    echo "<td>$name</td>";
    echo "<td>$description</td>";
    echo "<td>$quantity</td>";
    echo "<td>$itemPrice</td>";
    echo "<td>$itemTotal</td></tr>";
}

echo " <tfoot>
        <tr>
            <th colspan='5'></th>
            <th id = 'total' colspan='1'>Total </th>
            <td>$vendorTotal</td>
        </tr>
      </tfoot>
      </table> 
      </div> 
      <div id='endInfo'>
      <br>
      </div>
        <form align='center' action='../Front%20End/public_html/tabs.html'>
            <input type='submit' value='Return Home'>
        </form>
      <p align= 'center'>
        <input type='button' value='Create PDF' id='btPrint' onclick='createPDF()'/>
      </p>
      </body>
      <script>
        function createPDF() 
        {
            var tabInfo = document.getElementById('info').innerHTML
            var sTable = document.getElementById('tab').innerHTML;            
            var endinfo = document.getElementById('endInfo').innerHTML;
            var style = '<style>';
            style = style + 'table {width: 100%;font: 17px Calibri;}';
            style = style + 'table, th, td {border: solid 1px #DDD; border-collapse: collapse;';
            style = style + 'padding: 2px 3px;text-align: center;}';
            style = style + '</style>';

            // CREATE A WINDOW OBJECT.
            var win = window.open('', '', 'height=700,width=700');

            win.document.write('<html><head>');
            win.document.write('<title>Report</title>');   // <title> FOR PDF HEADER.
            win.document.write(style);          // ADD STYLE INSIDE THE HEAD TAG.
            win.document.write('</head>');
            win.document.write('<body>');
            win.document.write('<h3>');
            win.document.write(tabInfo);
            win.document.write('</h3>');
            win.document.write(sTable);         // THE TABLE CONTENTS INSIDE THE BODY TAG.
            win.document.write('<h3>');
            win.document.write(endinfo);
            win.document.write('</h3>');
            win.document.write('</body></html>');

            win.document.close(); 	// CLOSE THE CURRENT WINDOW.

            win.print();    // PRINT THE CONTENTS.
        }
       </script>
       </html>";


sqlsrv_free_stmt($stmt);








