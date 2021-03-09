<?php
echo('<script type="text/javascript">alert("Vendor successfully updated!");location="../Front End/public_html/tabs.html";</script>');
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
    $name = strval($_POST[evname]);
    $address = strval($_POST[evaddress]);
    $city = strval($_POST[evcity]);
    $state = strval($_POST[evstate]);
    $zip = intval($_POST[evzip]);
    $phone = intval($_POST[evphone]);
    $email = strval($_POST[evmail]);

    //SQL to add the Vendor to the vendor_table
    $sql = "UPDATE Vendor_Table SET Vendor_Name = '$name', Vendor_Address = '$address',
            Vendor_City = '$city', Vendor_State = '$state', Vendor_Zip_Code = $zip, Vendor_Phone = $phone, Vendor_Email = '$email'
            Where Vendor_ID = ?";
    $params = array($vendorID);

    $query = sqlsrv_query($conn,$sql,$params);

    if( !$query )
    {
        die( print_r( sqlsrv_errors(), true));
    }

    sqlsvr_close($conn);
}
exit;
