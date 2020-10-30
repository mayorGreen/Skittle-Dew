var express = require('express');
var app = express();


// This bit of code should start a webserver at http://localhost:5000 so that you can check to see that the information written to the database has actually be written.
// Optionally you can use the SELECT SQL statement in the MSSMS to ensure that the data has been written to the table
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