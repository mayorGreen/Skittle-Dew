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
    var x = document.createElement("option");
    x.textContent = " ";
    ven.appendChild(x);

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

    returnAjax('getter.php', function(data) {
        // console.log(data); // debug
        for (var i = 0; i < data.length-1; i++) {
            vendors.push([data[i].name, data[i].id]);
        }
        console.log(vendors);
        console.log(vendors.length);

        for (var i = 0; i < vendors.length; i++) {
            var name = vendors[i][0];
            var ID = vendors[i][1];
            var element = document.createElement("option");
            element.textContent = name;
            element.value = ID;
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

            var userName = document.createElement("input");
            userName.type = "text";
            userName.placeholder = "Username";

            var countyOffice = document.createElement("input");
            countyOffice.type = "text";
            countyOffice.placeholder = "County Office";

            var conNum = document.createElement("input");
            conNum.type = "text";
            conNum.placeholder = "Contract Number";

            var itemName = document.createElement("input");
            itemName.type = "text";
            itemName.placeholder = "Item Name";

            var orderDate = document.createElement("input");
            orderDate.type = "date";
            orderDate.placeholder = "Order Date";


/*
//This section is meant to add an order complete and order void checkbox
//I was unable to get it to work for some reason and hoped you could fix it.
//When checked the boxes should give a value of 1 (yes) to the database
//If a box is unchecked then it should send a 0 (no) to the database

            var orderComplete = document.createElement("input");
            orderComplete.type = "checkbox";
            orderComplete.id = "order_complete";
            orderComplete.name = "Order Complete";

            var complete = document.createElement("label");
            complete.type = "label";
            complete.htmlFor = "order_complete";
            complete.textContent = "Order Complete ";

            var orderVoid = document.createElement("input");
            orderVoid.type = "checkbox";
            orderVoid.id = "order_void";
            orderVoid.placeholder = "Order Void";

            var oVoid = document.createElement("label");
            oVoid.type = "label";
            oVoid.htmlFor = "order_void";
            oVoid.textContent = "Order Void ";
*/
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

            var totalPrice = document.createElement("input");
            totalPrice.id = "totalPrice";
            totalPrice.type = "number";
            totalPrice.placeholder = "Total Price";

            var notes = document.createElement("input");
            notes.type = "text";
            notes.placeholder = "Notes";


            newDiv.appendChild(userName);
            newDiv.appendChild(countyOffice);
            newDiv.appendChild(conNum);
            newDiv.appendChild(itemName);
            newDiv.appendChild(orderDate);
            newDiv.appendChild(orderComplete);
            //newdiv.appendChild(complete);
            //newdiv.appendChild(orderVoid);
            //newdiv.appendChild(oVoid);
            newDiv.appendChild(desc);
            newDiv.appendChild(quantity);
            newDiv.appendChild(price);
            newDiv.appendChild(totalPrice);
            newDiv.appendChild(notes);
            
            newDiv.appendChild(document.createElement("br"));

            itemDiv.append(newDiv);
            //itemDiv.style.visibility = "visible";
        }
    }

    var itemDiv = document.getElementById("itemDiv");
    var liDiv = document.createElement("div");

    var userName = document.createElement("input");
    userName.type = "text";
    userName.placeholder = "Username";

    var countyOffice = document.createElement("input");
    countyOffice.type = "text";
    countyOffice.placeholder = "County Office";

    var conNum = document.createElement("input");
    conNum.type = "text";
    conNum.placeholder = "Contract Number";

    var itemName = document.createElement("input");
    itemName.type = "text";
    itemName.placeholder = "Item Name";

    var orderDate = document.createElement("input");
    orderDate.type = "date";
    orderDate.placeholder = "Order Date";
/*
    var orderComplete = document.createElement("input");
    orderComplete.type = "checkbox";
    orderComplete.id = "order_complete";
    complete.name = "Order Complete";

    var complete = document.getElementById("label");
    complete.type = "label";
    complete.htmlFor = "order_complete";
    complete.textContent = "Order Complete ";

    var orderVoid = document.createElement("input");
    orderVoid.type = "checkbox";
    orderVoid.id = "order_void";
    orderVoid.placeholder = "Order Void";

    var oVoid = document.createElement("label");
    oVoid.type = "label";
    oVoid.htmlFor = "order_void";
    oVoid.textContent = "Order Void ";
*/
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

    var totalPrice = document.createElement("input");
    totalPrice.id = "totalPrice";
    totalPrice.type = "number";
    totalPrice.placeholder = "Total Price";

    var notes = document.createElement("input");
    notes.type = "text";
    notes.placeholder = "Notes";


    liDiv.appendChild(userName);
    liDiv.appendChild(countyOffice);
    liDiv.appendChild(conNum);
    liDiv.appendChild(itemName);
    liDiv.appendChild(orderDate);
    //liDiv.appendChild(orderComplete);
    //liDiv.appendChild(complete);
    //livDiv.appendChild(orderVoid);
    //liDiv.appendChild(oVoid);
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

        var finalizeButton = document.createElement("button");
        finalizeButton.type = "button";
        finalizeButton.textContent = "Finalize Item Selection";

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
    console.log(vName.value, vMail.value, vPhone.value, vAddress.value, vCity.value, vState.value, vZip.value);
    return true;
}