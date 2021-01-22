// Copyright (C) Skittle-Dew 2020. All Rights Reserved.
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
    document.getElementsById(tabName).style.display = "block";
    event.currentTarget.className += "active";

    if (tabName == "addOrder") {
        getVendors(sessionStorage.getItem("savedVendor"));
    }
}

function getVendors(setTo = null) {
    $.ajax({
        url:"getter.php",
        type: "post",
        datatype: 'json',
        success: function (response)
    });
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
    if (setTo != null) {
        ven.value = setTo;
    }
}

function onVSelect() {
    var vendor = document.getElementById("vendSelect");
    sessionStorage.setItem("savedVendor", vendor.value);

    var itemDiv = document.getElementById("itemDiv");
    itemDiv.innerHTML = '';

    var header = document.createElement("h3");
    header.textContent = "Items: ";
    
    itemDiv.appendChild(header);
    getItems();
}

function getItems() {
    function newItems(called = false) { // for add item button
        if (called == true) {
            console.log("newItems() called");
            newDiv = document.createElement("div");
            var conNum = document.createElement("input");
            conNum.type = "text";
            conNum.placeholder = "Contract Number";

            var desc = document.createElement("input");
            desc.type = "text";
            desc.placeholder = "Item Description";

            var quantity = document.createElement("input");
            quantity.id = "quantity";
            quantity.type = "number";
            quantity.step = "1";
            quantity.value = "1";
            quantity.min = "1";
            quantity.placeholder = "Quantity";

            var price = document.createElement("input");
            price.id = "price";
            price.type = "number";
            price.min = "0.01";
            price.step = "any";
            price.placeholder = "Price";

            newDiv.appendChild(conNum);
            newDiv.appendChild(desc);
            newDiv.appendChild(quantity);
            newDiv.appendChild(price);
            
            newDiv.appendChild(document.createElement("br"));

            itemDiv.append(newDiv);
            //itemDiv.style.visibility = "visible";
        }
    }

    var itemDiv = document.getElementById("itemDiv");
    var liDiv = document.createElement("div");

    var conNum = document.createElement("input");
    conNum.type = "text";
    conNum.placeholder = "Contract Number";

    var desc = document.createElement("input");
    desc.type = "text";
    desc.placeholder = "Item Description";

    var quantity = document.createElement("input");
    quantity.id = "quantity";
    quantity.type = "number";
    quantity.step = "1";
    quantity.value = "1";
    quantity.min = "1";
    quantity.placeholder = "Quantity";

    var price = document.createElement("input");
    price.id = "price";
    price.type = "number";
    price.min = "0.01";
    price.step = "any";
    price.placeholder = "Price";

    liDiv.appendChild(conNum);
    liDiv.appendChild(desc);
    liDiv.appendChild(quantity);
    liDiv.appendChild(price);
    
    liDiv.appendChild(document.createElement("br"));
    itemDiv.append(liDiv);

    var buttonsDiv = document.getElementById("buttonsDiv");
    
    var addItemButton = document.createElement("button");
    addItemButton.type = "button";
    addItemButton.textContent = "Click to add new item";
    addItemButton.onclick = function() {newItems(true)};

    var finalizeButton = document.createElement("button");
    finalizeButton.type = "button";
    finalizeButton.textContent = "Finalize Item Selection";

    buttonsDiv.append(addItemButton);
    buttonsDiv.append(finalizeButton);

    buttonsDiv.visibility = "visible";
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

function validateState(state) {
    // TODO: fill in
    return 0
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
    return true;
}
