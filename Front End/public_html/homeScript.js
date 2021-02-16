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
    document.getElementById(tabName).style.display = "block";
    event.currentTarget.className += "active";

    if (tabName == "addOrder") {
        getVendors(sessionStorage.getItem("savedVendor"));
    }
}

function getVendors(setTo = null) {
    var ven = document.getElementById("vendSelect");
    ven.innerHTML = '';

    var vendors = [[" ", 0]];
    
    function returnAjax(url, callbackFunc, called = false) {
        if (called == true) {
            httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) { // correct codes
                    // process server response
                    //console.log(this);
                    //console.log(this.responseText);
                    try {
                        var data = JSON.parse(httpRequest.responseText);
                    } catch(err) {
                        console.log(err.message + " in " + httpRequest.responseText);
                        return;
                    }
                    callbackFunc(data);
                }
            };
            httpRequest.open("GET", url, true);
            httpRequest.send();
        }
    }

    returnAjax('../../Back End/getter.php', function(data) {
        // console.log(data); // debug
        for (var i = 0; i < data.length-1; i++) {
            vendors.push([data[i].name, data[i].id]);
        }
        vendors.sort();
        console.log(vendors);
        console.log(vendors.length);

        for (var i = 0; i < vendors.length; i++) {
            var name = vendors[i][0];
            var vID = vendors[i][1];
            var element = document.createElement("option");
            element.textContent = name;
            element.name = "vName";
            element.value = vID;

            ven.appendChild(element);
        }
        if (setTo != null) {
            ven.value = setTo;
        }
    }, true);
}

    

function onVSelect() {
    var vendor = document.getElementById("vendSelect");
    sessionStorage.setItem("savedVendor", vendor.value);

    var form = document.getElementById("oCreate");

    if (document.getElementById("vID") == null) {
        var vID = document.createElement("input");
        vID.type = "hidden";
        vID.value = vendor.options[vendor.selectedIndex].value;
        vID.id = "vID";
        vID.name = "vID";

        var vName = document.createElement("input");
        vName.type = "hidden";
        vName.value = vendor.options[vendor.selectedIndex].text;
        vName.id = "vName";
        vName.name = "vName";

        form.appendChild(vID);
        form.appendChild(vName);
    } else {
        var vIDAgain = document.getElementById("vID");
        vIDAgain.value = vendor.options[vendor.selectedIndex].value;

        var vNameAgain = document.getElementById("vName");
        vNameAgain.value = vendor.options[vendor.selectedIndex].text;
    }



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
            newDiv = document.createElement("div");

            var itemName = document.createElement("input");
            itemName.type = "text";
            itemName.placeholder = "Item Name";
            itemName.name = "iName";

            var desc = document.createElement("input");
            desc.type = "text";
            desc.placeholder = "Item Description";
            desc.name = "oDescription";

            var quantity = document.createElement("input");
            quantity.id = "quantity";
            quantity.type = "number";
            quantity.step = "1";
            quantity.value = "1";
            quantity.min = "1";
            quantity.placeholder = "Quantity";
            quantity.name = "oQuantity";

            var price = document.createElement("input");
            price.id = "price";
            price.type = "number";
            price.min = "1";
            price.step = "1";
            price.placeholder = "Price";
            price.name = "uPrice";

            var totalPrice = document.createElement("input");
            totalPrice.id = "totalPrice";
            totalPrice.type = "number";
            totalPrice.placeholder = "Total Price";
            totalPrice.name = "tPrice";

            var notes = document.createElement("input");
            notes.type = "text";
            notes.placeholder = "Notes";
            notes.name = "note";

            newDiv.appendChild(itemName);
            newDiv.appendChild(desc);
            newDiv.appendChild(quantity);
            newDiv.appendChild(price);
            newDiv.appendChild(totalPrice);
            newDiv.appendChild(notes);
            
            newDiv.appendChild(document.createElement("br"));

            itemDiv.append(newDiv);
            itemDiv.style.visibility = "visible";
        }
    }

    var infoDiv = document.getElementById("infoDiv");
    var itemDiv = document.getElementById("itemDiv");
    var liDiv = document.createElement("div");

    if (infoDiv.visibility != "visible") {
        var userName = document.createElement("input");
        userName.type = "text";
        userName.placeholder = "Username";
        userName.id = "user_name";
        userName.name = "uLogin";


        var utc = new Date().toJSON().slice(0,16);
        console.log(utc)

        var orderDate = document.createElement("input");
        orderDate.type = "datetime-local";
        orderDate.value = utc;
        orderDate.id = "date"
        orderDate.name = "oDate";

        var orderComplete = document.createElement("input");
        orderComplete.type = "checkbox";
        orderComplete.id = "order_complete";
        orderComplete.name = "oComplete";

        var orderVoid = document.createElement("input");
        orderVoid.type = "checkbox";
        orderVoid.id = "order_void";
        orderVoid.name = "oVoid";

        var orderLabel = document.createElement("span");
        orderLabel.textContent = " Order Complete: "

        var voidLabel = document.createElement("span");
        voidLabel.textContent = " Void Complete: "

        infoDiv.appendChild(userName);
        infoDiv.appendChild(orderDate);
        infoDiv.appendChild(orderLabel);
        infoDiv.appendChild(orderComplete);
        infoDiv.appendChild(voidLabel);
        infoDiv.appendChild(orderVoid);

        infoDiv.visibility = "visible";
    }

    var itemName = document.createElement("input");
    itemName.type = "text";
    itemName.placeholder = "Item Name";
    itemName.name = "iName";

    var desc = document.createElement("input");
    desc.type = "text";
    desc.placeholder = "Item Description";
    desc.name = "oDescription";

    var quantity = document.createElement("input");
    quantity.id = "quantity";
    quantity.type = "number";
    quantity.step = "1";
    quantity.value = "1";
    quantity.min = "1";
    quantity.placeholder = "Quantity";
    quantity.name = "oQuantity";

    var price = document.createElement("input");
    price.id = "price";
    price.type = "number";
    price.min = "1";
    price.step = "any";
    price.placeholder = "Price";
    price.name = "uPrice";

    var totalPrice = document.createElement("input");
    totalPrice.id = "totalPrice";
    totalPrice.type = "number";
    totalPrice.placeholder = "Total Price";
    totalPrice.name = "tPrice";

    var notes = document.createElement("input");
    notes.type = "text";
    notes.placeholder = "Notes";
    notes.name = "note";


    liDiv.appendChild(itemName);
    liDiv.appendChild(desc);
    liDiv.appendChild(quantity);
    liDiv.appendChild(price);
    liDiv.appendChild(totalPrice);
    liDiv.appendChild(notes);
    
    liDiv.appendChild(document.createElement("br"));
    itemDiv.append(liDiv);

    var buttonsDiv = document.getElementById("buttonsDiv");

    if (buttonsDiv.visibility != "visible") {
        var addItemButton = document.createElement("button");
        addItemButton.type = "button";
        addItemButton.textContent = "Click to add new item";
        addItemButton.onclick = function() {newItems(true)};

        var finalizeButton = document.createElement("input");
        finalizeButton.type = "submit";
        finalizeButton.value = "Finalize Item Selection";
        finalizeButton.name = "submit";

        buttonsDiv.append(addItemButton);
        buttonsDiv.append(finalizeButton);

        buttonsDiv.visibility = "visible";
    }
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

function newVendor(vName, vcontract, vMail, vPhone, vAddress, vCity, vState, vZip) {
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
    if (vAddress.value.length < 1) {
        warn.textContent = "Address Field is required!";
        warn.style.visibility = "visible";
        return false;
    }

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
    if (vState.value == "NIL") {
        warn.textContent = "You must choose a State!";
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
    console.log(vName.value, vcontract.value, vMail.value, vPhone.value, vAddress.value, vCity.value, vState.value, vZip.value);
    return true;
}