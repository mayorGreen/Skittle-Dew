<?php
if(isset($_POST['submit']))
{
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array( "Database"=>"Buchanan County P.O. System", "UID"=>"sa", "PWD"=>"@MissouriWestern");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);


    if( $conn )
    {
        echo "Connection established.";


    }else
    {
        echo "Connection could not be established.";
        die(print_r(sqlsrv_errors(), true));
    }


    $name = strval($_POST[vname]);
    $address = strval($_POST[vaddress]);
    $city = strval($_POST[vcity]);
    $state = strval($_POST[vstate]);
    $zip = intval($_POST[vzip]);
    $phone = intval($_POST[vphone]);
    $email = strval($_POST[vmail]);
    echo  "'$name','$address','$city','$state',$zip,$phone,'$email'";


    $sql = "SET ANSI_WARNINGS OFF SET IDENTITY_INSERT Vendor_Table ON Insert INTO Vendor_Table(Vendor_ID,Vendor_Name,Vendor_Address,Vendor_City,Vendor_State,Vendor_Zip_Code,Vendor_Phone,Vendor_Email) 
    VALUES(NEWID(),?,?,?,?,?,?,?) SET ANSI_WARNINGS ON";
    $params = array($name, $address,$city,$state,$zip,$phone,$email);

    $query = sqlsrv_query($conn,$sql,$params);

    if( $query )
    {
        echo "Row successfully inserted.\n";
    }
    else
    {
        echo "Row insertion failed.\n";
        die( print_r( sqlsrv_errors(), true));
    }

    sqlsvr_close($conn);

}



