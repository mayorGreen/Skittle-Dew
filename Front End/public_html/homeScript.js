// Copyright (C) Skittle-Dew 2020
// Handles all index.html javascript
function openTab(event, tabName) {
    var i, tabcontent, tablinks;

    // hide unused tabs
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace("active", "");
    }

    // Display current tab
    document.getElementById(tabName).style.display = "block";
    event.currentTarget.className += "active";

    if (tabName == "addOrder") {
        getVendors();
    }
}
function getVendors() {
    var ven = document.getElementById("vendSelect");
    ven.innerHTML = '';
    var x = document.createElement("option");
    x.textContent = " ";
    ven.appendChild(x);
    // TODO: Insert Database functionality, replace this demo
    /*
    // THIS IS PSEUDOCODE, PLEASE REVIEW IT BEFORE YOU UNCOMMENT THIS AND CALL IT A DAY. It should go something like this though, if I know what I'm doing.
    for(var i = 0; i < vendVals.length; i++) { // import vendVals from database, through index.js
        var vendor = vendVals[i];
        var element = document.createElement("option");
        element.textContent = vendor["name"];
        element.value = vendor["ID"];
        ven.appendChild(element);
    }
    */
    var arr = [["Initech", 1], ["Falconghini", 2], ["Aperture Science", 3]];
    for (var i = 0; i < arr.length; i++) {
        var name = arr[i][0];
        var ID = arr[i][1];
        var element = document.createElement("option");
        element.textContent = name;
        element.value = ID;
        ven.appendChild(element);
    }
}
function getItems() {
    var itemDiv = document.getElementById("itemDiv");
    itemDiv.innerHTML = '';

    var vendor = document.getElementById("vendSelect");
    var vID = vendor.value;

    // From here, this is where we'd use the vID to find the item table to pull it
    // However for now I'll be using placeholder values
    var dict = {
        1: ["TPS Report", "Computer Chip"],
        2: ["Falconghini", "Vapid"],
        3: ["Portal Gun", "Gravity Gun"]
    };


    var select = document.createElement("select");
    var x = document.createElement("option");
    x.textContent = " ";
    select.appendChild(x);
    for (i = 0; i < dict[vID].length; i++) {
        var itemName = dict[vID][i];
        var n = document.createElement("option");
        n.textContent = itemName;
        n.value = itemName;
        select.appendChild(n);
    }
    select.onchange = "newItems()"; // newItems function doesn't exist

    var quantity = document.createElement("input");
    quantity.type = "number";
    quantity.step = "1";
    quantity.value = "1";

    var br = document.createElement("br");

    var button = document.createElement("button");
    button.type = "button";
    button.textContent = "Finalize Item Selection";

    itemDiv.appendChild(select);
    itemDiv.appendChild(quantity);
    itemDiv.appendChild(br);
    itemDiv.appendChild(button);

    itemDiv.style.visibility = "visible";
}
function validatePhone(phoneNo) {
    var noDash = /^\d{10}$/;
    var dashDotOrSpace = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    if ((phoneNo.match(noDash)) || (phoneNo.match(dashDotOrSpace))) {
        return true;
    } else {
        return false;
    }
}
function validateZip(zip) {
    var vZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/;
    if (zip.match(vZip)) {
        return true;
    } else {
        return false;
    }
}

function newVendor(vName, vMail, vPhone, vAddress, vCity, vState, vZip) {
    var warn = document.getElementById("avWarn");
    warn.style.visibility = "hidden";

    validEmailSuffixes = [".com", ".net", ".gov", ".edu"]; // add more as nessesary

    // update these to match exact submission criteria to prevent bad data entry
    // validate name field length
    if (vName.value.length <= 1) {
        warn.textContent = "Vendor Name is too short!";
        warn.style.visibility = "visible";
        return false;
    }

    if (vName.value.length >= 255) {
        warn.textContent = "Vendor Name is too long!";
        warn.style.visibility = "visible";
        return false;
    }

    // validate Email Address ending
    vMailSuffix = vMail.value.substr(vMail.value.length - 4);
    for (var i = 0; i < validEmailSuffixes.length; i++){
        if (vMailSuffix == validEmailSuffixes[i]){
            break
        }
    }
    if (i == validEmailSuffixes.length) {
        warn.textContent = "Invalid Email Address!";
        warn.style.visibility = "visible";
        return false;
    }

    // validate @ symbol present in email
    for (var i = 0; i < vMail.value.length; i++){
        if (vMail.value[i] == '@'){
            break
        }
    }
    if (i == vMail.value.length) {
        warn.textContent = "Invalid Email Address!";
        warn.style.visibility = "visible";
        return false;
    }

    // validate phone number
    phoneValid = validatePhone(vPhone.value);
    if (phoneValid == false) {
        warn.textContent = "Invalid Phone Number!";
        warn.style.visibility = "visible";
        return false;
    }

    // validate address
    // TODO: validate address field

    // validate city
    if (vCity.value.length <= 1) {
        warn.textContent = "City Name is too short!";
        warn.style.visibility = "visible";
        return false;
    }

    if (vCity.value.length >= 255) {
        warn.textContent = "City Name is too long!";
        warn.style.visibility = "visible";
        return false;
    }

    // validate state
    if (vState.value.length != 2) {
        warn.textContent = "Invalid State!";
        warn.style.visibility = "visible";
        return false;
    }

    // validate Zip Code
    var validZip = validateZip(vZip.value);
    if (validZip == false) {
        warn.textContent = "Invalid Zip Code!";
        warn.style.visibility = "visible";
        return false;
    }

    console.log("All tests were succesful!");
    console.log("This is usually where I'd push things to the database, but you know");
    return true;
}
