<?php

if (isset($_POST['submit'])) {
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array("Database" => "Buchanan County P.O. System", "UID" => "sa", "PWD" => "@MissouriWestern");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }

    $user = $_POST['userLogin'];
    $pass = $_POST['userPassword'];

    $sql = "SELECT * FROM User_Table WHERE User_Login = ? AND User_Password = ?";
    $params = array($user, $pass);

    $result = sqlsrv_query($conn, $sql, $params);
    $rows = sqlsrv_has_rows($result);
    if ($rows == 1) {
        //echo "Success";
        Header( 'Location: sucessLogin.php?success=1' );
    }
    else{
        Header( 'Location: failedLogin.php?failed=1' );
        //echo "Invalid username/password";
        //echo "Row count = $rows";
    }
    sqlsvr_close($conn);
}
exit;