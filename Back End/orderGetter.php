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
$sql = "SELECT Order_ID, User_Login, Order_Date, Order_Complete, Order_Void FROM Order_Table WHERE Vendor_ID = ? AND Order_Date Between ? AND ? ";
$params = array($vendorID,$orderDate1,$orderDate2);

$stmt = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

/*
 * We are querying the database for the order total price, and the order void and complete status.
 * We may not need these and may just wish to total the entirety of the orders made by the vendor within the date period.
 * If we do not need these they can be taken out or left in in case we decide to use them later.
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
$data = array();


//Appends order ids to params2 and adds an extra variable to each sql statement
//very sloppy usage, but it works
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    $id2 = $row['Order_ID'];
    $login = $row['User_Login'];
    $date = $row['Order_Date'];
    $date = $date->format('m-d-Y');
    $complete = $row['Order_Complete'];
    $void = $row['Order_Void'];

    array_push($data, $id2, $login, $date, $complete, $void);
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

//begining of html data to create a table
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
<table align='center'>";

echo $html1;

$j = 1;
$f = 1;
$numItems = 0;
$data2 = array();

//adds all rows in the line item table to an array
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))
{
    $id = $row['Order_ID'];
    $line_item = $row['Line_Item'];
    $contract = $row['Contract_Number'];
    $name = $row['Item_Name'];
    $description = $row['Item_Description'];
    $quantity = $row['Item_Quantity'];
    $itemPrice = doubleval($row['Item_Price']);
    $itemTotal = doubleval($row['Total_Price']);

    if ($contract == 0)
    {
        $contract = "Non-Contract";
    }
    $vendorTotal += $itemTotal;

    //Converts numbers to number format for better readability
    $itemTotal = number_format($itemTotal,2);
    $itemPrice = number_format($itemPrice,2);

    array_push($data2, $id,$line_item,$contract,$name,$description,$quantity,$itemPrice,$itemTotal);
    $numItems ++;
}//end while

//converts Total to a number format
$vendorTotal = number_format($vendorTotal,2);

//loops through both arrays and creates a table based on the orders and the items contained in the orders
for($i = 0; $i<count($params2);$i++)
{
    echo" <tr>
            <th colspan='2'>Ordered By</th>
            <th>Order Date</th>
            <th colspan='2'>Order Complete</th>
            <th colspan='2'>Order Void</th>
         </tr>";
    echo "<tr><td colspan='2'>$data[$j]</td>";
    $j++;
    echo "<td>$data[$j]</td>";
    $j++;
    if($data[$j] == 0){$data[$j] = "No";}else $data[$j] = "Yes";
    echo "<td colspan='2'>$data[$j]</td>";
    $j++;
    if($data[$j] == 0){$data[$j] = "No";}else $data[$j] = "Yes";
    echo "<td colspan='2'>$data[$j]</td>";
    $j++;
    $j++;
    echo "<tr>
            <th>Line Item</th>
            <th>Contract</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total Price</th>
           </tr>";

    for($h = 0;$h<=count($data2);$h++)
    {

        if(strcmp($params2[$i], $data2[$h])==0)
        {
            echo "<tr><td>$data2[$f]</td>";
            $f++;
            echo "<td>$data2[$f]</td>";
            $f++;
            echo "<td>$data2[$f]</td>";
            $f++;
            echo "<td>$data2[$f]</td>";
            $f++;
            echo "<td>$data2[$f]</td>";
            $f++;
            echo "<td>$data2[$f]</td>";
            $f++;
            echo "<td>$data2[$f]</td></tr>";
            $f++;
            $f++;
        }//end if
    }//end for
    echo "<tr><td colspan='7' height='5px'></td></tr>";
}//end for

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
            win.document.write('<title>Buchanan County</title>');   // <title> FOR PDF HEADER.
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








