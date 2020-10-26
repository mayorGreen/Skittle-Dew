const express = require("express");
require("dotenv").config();

const app = express();
console.log(process.env.PORT);

const port = process.env.PORT;
app.listen(port,() => console.log('listening on port '+port));
app.use(express.static("public_html"));