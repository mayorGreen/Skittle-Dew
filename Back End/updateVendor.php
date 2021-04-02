<?php
echo('<script type="text/javascript">alert("Vendor successfully updated!");location="../Buch_County/tabs.html";</script>');
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
    $vendorID = $_COOKIE[vendorID];
    $name = strval($_POST['evname']);
    $address = strval($_POST['evaddress']);
    $city = strval($_POST['evcity']);
    $state = strval($_POST['evstate']);
    $zip = intval($_POST['evzip']);
    $phone = intval($_POST['evphone']);
    $email = strval($_POST['evmail']);

    //SQL to add the Vendor to the vendor_table
    $sql = "UPDATE Vendor_Table SET Vendor_Name = ?, Vendor_Address = ?,
            Vendor_City = ?, Vendor_State = ?, Vendor_Zip_Code = ?, Vendor_Phone = ?, Vendor_Email = ?
            WHERE Vendor_ID = ?;";
    $params = array($name, $address, $city, $state, $zip, $phone, $email, $vendorID);

    $query = sqlsrv_query($conn,$sql,$params);

    if( !$query )
    {
        die( print_r( sqlsrv_errors(), true));
    }

    sqlsvr_close($conn);
}
exit;
