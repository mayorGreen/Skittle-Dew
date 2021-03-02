<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
$conn = sqlsrv_connect($serverName, $connectionInfo);


if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$vendorID = $_COOKIE["vID"];
$vendorName = $_COOKIE["vName"];
$orderDate1 = $_COOKIE["date1"];
$orderDate2 = $_COOKIE["date2"];
$vendorTotal = 0.0;

$sql = "SELECT Order_ID FROM Order_Table WHERE Vendor_ID = ? AND Order_Date Between ? AND ? ";
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
}

$sql2 = "Select * FROM Line_Item_Table WHERE Order_ID = ?";
$params2 = array($id2);

$stmt2 = sqlsrv_query($conn, $sql2, $params2);

if (!$stmt2) {
    die(print_r(sqlsrv_errors(), true));
}

$html1 = "<html> <head> <style>
table {font-family: arial, sans-serif; border-collapse: collapse; width: 100%;}
td, th { border: 1px solid #dddddd; text-align: center; padding: 4px;}
tr:nth-child() {background-color: #dddddd;}
</style>
</head>
<body>
<div id='info'>
<h2>Report for $vendorName</h2>
<h3>Between $orderDate1 and $orderDate2</h3>
</div>
<div id='tab'>
<table>
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

    $vendorTotal += $itemTotal;

    echo "<tr><td>$line_item</td>";
    echo "<td>$contract</td>";
    echo "<td>$name</td>";
    echo "<td>$description</td>";
    echo "<td>$quantity</td>";
    echo "<td>$itemPrice</td>";
    echo "<td>$itemTotal</td></tr>";
}

echo "</table> 
      </div> 
      <div id='endInfo'>
      <h3>Total spent by $vendorName between $orderDate1 and $orderDate2 is: $$vendorTotal</h3>
      </div>
      <form action='../Front%20End/public_html/tabs.html'>
      <input type='submit' value='Return Home'>
      </form>
      <p>
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








