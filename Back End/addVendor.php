<?php
echo('<script type="text/javascript">alert("Vendor successfully created!");location="../Front End/public_html/tabs.html";</script>');
if(isset($_POST['submit']))
{
    //Database connection script
    $serverName = "WIN-RUH4VKA5M7L\SQLEXPRESS"; //serverName\instanceName
    $connectionInfo = array( "Database"=>"Buchanan County P.O. System", "UID"=>"sa", "PWD"=>"@MissouriWestern");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);


    if( !$conn )
    {
        die(print_r(sqlsrv_errors(), true));
    }

    //Vendor Variables
    $name = strval($_POST[vname]);
    $address = strval($_POST[vaddress]);
    $city = strval($_POST[vcity]);
    $state = strval($_POST[vstate]);
    $zip = intval($_POST[vzip]);
    $phone = intval($_POST[vphone]);
    $email = strval($_POST[vmail]);

    //SQL to add the Vendor to the vendor_table
    $sql = "SET ANSI_WARNINGS OFF SET IDENTITY_INSERT Vendor_Table ON Insert INTO Vendor_Table(Vendor_ID,Vendor_Name,Vendor_Address,Vendor_City,Vendor_State,Vendor_Zip_Code,Vendor_Phone,Vendor_Email) 
    VALUES(NEWID(),?,?,?,?,?,?,?) SET ANSI_WARNINGS ON";
    $params = array($name,$address,$city,$state,$zip,$phone,$email);

    $query = sqlsrv_query($conn,$sql,$params);

    if( !$query )
    {
        die( print_r( sqlsrv_errors(), true));
    }

    sqlsvr_close($conn);
}
exit;






