var express = require('express');
var app = express();

app.get('/', function (req, res)
{
    var sql = require("mssql");

    // config for your database
    var config =
    {
        user: 'sa',
        password: '@MissouriWestern',
        server: 'WIN-RUH4VKA5M7L\\SQLEXPRESS',
        database: 'Buchanan County P.O. System'
        //multipleStatement: true
    };

    sql.connect(config, function (err)
    {
        if (err) console.log(err)
        var request = new sql.Request()

        var inserting = "SET IDENTITY_INSERT Vendor_Table ON Insert INTO Vendor_Table(Vendor_ID, Vendor_Name, Vendor_Address, Vendor_City, Vendor_State, Vendor_Zip_Code, Vendor_Phone, Vendor_Email)" +
            " Values(NEWID(), 'MWSU', 'MWSU Drive', 'Saint Joseph', 'MO', 64506, 6605554444, 'missouriwestern@missouriwestern.edu')"

        request.query(inserting, function (err, data)
        {
            if (err) console.log(err)

        })

    })

    // connect to your database
    sql.connect(config, function (err)
    {
        if (err) console.log(err);

        // create Request object
        var request = new sql.Request();


        // query to the database and get the records
        request.query('SELECT * FROM Vendor_Table', function (err, recordset)
        {
            if (err) console.log(err)

            //send records as a response
            res.send(recordset)

        })
    })
})

var server = app.listen(5000, function () {
    console.log('Server is running..');
});