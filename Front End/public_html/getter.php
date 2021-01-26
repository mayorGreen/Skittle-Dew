
<?php

    echo "hello world \n";
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }

    $sql = "SELECT Vendor_ID, Vendor_Name FROM Vendor_Table";

    $stmt = sqlsrv_query($conn, $sql);

    if (!$stmt) {
        die(print_r(sqlsrv_errors(), true));
    }

    $nameArray = array();

    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
        echo json_encode($row['Vendor_ID']);
        echo json_encode($row['Vendor_Name']);

    }

    sqlsrv_free_stmt($stmt);
    echo "finished";




