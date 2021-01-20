<?php
echo('<script type="text/javascript">alert("Vendor successfully created!");location="http://localhost/Buch_County/index.html";</script>');
if(isset($_POST['submit']))
{


    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array( "Database"=>"Buchanan County P.O. System", "UID"=>"sa", "PWD"=>"@MissouriWestern");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);


    if( !$conn )
    {
        die(print_r(sqlsrv_errors(), true));
    }


    $name = strval($_POST[vname]);
    $address = strval($_POST[vaddress]);
    $city = strval($_POST[vcity]);
    $state = strval($_POST[vstate]);
    $zip = intval($_POST[vzip]);
    $phone = intval($_POST[vphone]);
    $email = strval($_POST[vmail]);
    //echo  "'$name','$address','$city','$state',$zip,$phone,'$email'\r\n";


    $sql = "SET ANSI_WARNINGS OFF SET IDENTITY_INSERT Vendor_Table ON Insert INTO Vendor_Table(Vendor_ID,Vendor_Name,Vendor_Address,Vendor_City,Vendor_State,Vendor_Zip_Code,Vendor_Phone,Vendor_Email) 
    VALUES(NEWID(),?,?,?,?,?,?,?) SET ANSI_WARNINGS ON";
    $params = array($name, $address,$city,$state,$zip,$phone,$email);

    $query = sqlsrv_query($conn,$sql,$params);

    if( !$query )
    {
        die( print_r( sqlsrv_errors(), true));
        //echo "Row successfully inserted.\r\n";
    }
    //else
    //{
        //echo "Row insertion failed.\r\n";

    //}

    sqlsvr_close($conn);

}
exit;






