<?php
{
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if ($conn) {
        echo "Connection established.";
    } else {
        echo "Connection could not be established.";
        die(print_r(sqlsrv_errors(), true));
    }
}
