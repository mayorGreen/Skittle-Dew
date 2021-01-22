
<?php

$serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
$connectionInfo = array( "Database"=>"Buchanan County P.O. System", "UID"=>"sa", "PWD"=>"@MissouriWestern");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( !$conn )
{
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT Vendor_Name FROM Vendor_Table";

$query = sqlsrv_query($conn, $sql);

    if (!$query) {
        die(print_r(sqlsrv_errors(), true));
    }
    $nameArray = array();

    while ($data = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $name = $data['Vendor_Name'];
        $nameArray[] = array("name" => $name);
    }

    sqlsvr_close($conn);
    echo json_encode($nameArray);



