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

    if (tabName == "addOrder" || tabName == "createReports") {
        getVendors(tabName, sessionStorage.getItem("savedVendor"));
        if(tabName == "createReports") {
            var toDate = document.getElementById("date2");
            var date = new Date();
            var day = date.getDate().toString();
            var month = (date.getMonth()+1).toString();
            if (day.length <= 1) {
                day = "0" + day;
            }
            if (month.length <= 1) {
                month = "0" + month;
            }
            // console.log(date.getFullYear() + '-' + month + '-' + day);
            toDate.value = date.getFullYear() + '-' + month + '-' + day;
        }
    }
}

function returnAjax(url, callbackFunc, called = false) {
    if (called == true) {
        httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) { // correct codes
                // process server response
                console.log(this);
                console.log(this.responseText);
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

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }

    document.cookie = escape(name) + "=" +
        escape(value) + expires + "; path=/";
}

function getVendors(tName, setTo = null) {
    if (tName == "addOrder") {
        var ven = document.getElementById("vendSelect");
    }
    else if (tName == "createReports") {
        var ven = document.getElementById("reportVSelect");
    }
    else {
        console.log("Something's gone horribly wrong");
    }

    ven.innerHTML = '';

    var vendors = [[" ", 0]];

    returnAjax("../../Back End/vendorGetter.php", function(data) {
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

function onVSelect(where) {
    switch(where) {
        case "order":
            var vendor = document.getElementById("vendSelect");
            var form = document.getElementById("oCreate");
            break;
        case "report":
            var vendor = document.getElementById("reportVSelect");
            var form = document.getElementById("reportCreate");
            break;
        default:
            console.log("something's gone horribly wrong (onVSelect)");
    }

    sessionStorage.setItem("savedVendor", vendor.value);

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


    if (where == "order") {
        var itemDiv = document.getElementById("itemDiv");
        itemDiv.innerHTML = '';

        var header = document.createElement("h3");
        header.textContent = "Items: ";

        itemDiv.appendChild(header);
        getItems();
    }
}

function saveCookies() {
    createCookie("vID", document.getElementById("vID").value, "0.25");
    createCookie("vName", vName.value, "0.25");
    createCookie("date1", document.getElementById("date1").value, "0.25");
    createCookie("date2", document.getElementById("date2").value, "0.25");
    createCookie("conSelect", document.getElementById("contract").value, "0.25");
}

function editOrders()
{
    createCookie("orderID",document.getElementById("orderID").value, "0.25");
    var orders = [];
    returnAjax("../../Back End/editOrder.php", function(data) {
        //console.log(data); // debug
        for (var i = 1; i < data.length-1; i++) {
            orders.push([data[i].userLogin, data[i].orderDate, data[i].orderComplete, data[i].orderVoid, data[i].totalPrice, data[i].notes,data[i].lineItem,
            data[i].contract, data[i].itemName, data[i].itemDescription, data[i].itemQuantity, data[i].itemPrice, data[i].itemTotal]);
            userName.value = data[i].userLogin;
            //orderDate.value = data[i].orderDate;
            if(data[i].orderComplete === 1){orderComplete.checked = true; }
            if(data[i].orderVoid === 1){orderVoid.checked = true;}
            notes.value = data[i].notes;
            totalCost.value = data[i].totalPrice;
        }
        for(var j = 0; j <orders.length; j++)
        {
            newDiv = document.createElement("div");

            var lineItem = document.createElement("input");
            lineItem.type = "number";
            lineItem.value = orders[j][6];
            lineItem.name = "eitems[]lineItem";
            lineItem.style.display = "none";

            var contractNumber = document.createElement("input");
            contractNumber.type = "number";
            contractNumber.value = orders[j][7];
            contractNumber.placeholder = "Contract Number";
            contractNumber.name = "eitems[]cNumber";

            var itemName = document.createElement("input");
            itemName.type = "text";
            itemName.value = orders[j][8];
            itemName.placeholder = "Item Name";
            itemName.name = "eitems[]iName";

            var desc = document.createElement("input");
            desc.type = "text";
            desc.value = orders[j][9];
            desc.placeholder = "Item Description";
            desc.name = "eitems[]oDescription";

            var quantity = document.createElement("input");
            quantity.id = "quantity";
            quantity.type = "number";
            quantity.step = "1";
            quantity.value = orders[j][10];
            quantity.min = "1";
            quantity.placeholder = "Quantity";
            quantity.name = "eitems[]oQuantity";
            quantity.oninput = function() {eitemTotal(), eorderTotal()};

            var price = document.createElement("input");
            price.id = "price";
            price.value = orders[j][11];
            price.type = "number";
            price.min = "1";
            price.step = ".01";
            price.placeholder = "Price";
            price.name = "eitems[]uPrice";
            price.oninput = function() {eitemTotal(), eorderTotal()};

            var totalPrice = document.createElement("input");
            totalPrice.id = "totalPrice";
            totalPrice.value = orders[j][12];
            totalPrice.type = "number";
            totalPrice.step = ".01"
            totalPrice.placeholder = "Total Price";
            totalPrice.name = "eitems[]tPrice";

            newDiv.appendChild(lineItem);
            newDiv.appendChild(contractNumber);
            newDiv.appendChild(itemName);
            newDiv.appendChild(desc);
            newDiv.appendChild(quantity);
            newDiv.appendChild(price);
            newDiv.appendChild(totalPrice);

            newDiv.appendChild(document.createElement("br"));

            editItemDiv.append(newDiv);
            editItemDiv.style.visibility = "visible";
        }
    }, true);

    var form = document.getElementById("orderEdit");
    form.style.display = "block";

    var editInfoDiv = document.getElementById("editInfoDiv");
    var editItemDiv = document.getElementById("editItemDiv");

    if (editInfoDiv.visibility != "visible") {
        var userName = document.createElement("input");
        userName.type = "text";
        userName.placeholder = "Username";
        userName.id = "user_name";
        userName.name = "euLogin";

        var utc = new Date().toJSON().slice(0,10);

        var orderDate = document.createElement("input");
        orderDate.type = "date";
        orderDate.value = utc;
        orderDate.id = "date"
        orderDate.name = "eoDate";

        var orderComplete = document.createElement("input");
        orderComplete.type = "checkbox";
        orderComplete.id = "order_complete";
        orderComplete.name = "eoComplete";

        var orderVoid = document.createElement("input");
        orderVoid.type = "checkbox";
        orderVoid.id = "order_void";
        orderVoid.name = "eoVoid";

        var orderLabel = document.createElement("span");
        orderLabel.textContent = " Order Complete: "

        var voidLabel = document.createElement("span");
        voidLabel.textContent = " Order Void: "

        var notes = document.createElement("input");
        notes.type = "text";
        notes.placeholder = "Notes";
        notes.name = "enote";

        editInfoDiv.appendChild(userName);
        editInfoDiv.appendChild(orderLabel);
        editInfoDiv.appendChild(orderComplete);
        editInfoDiv.appendChild(voidLabel);
        editInfoDiv.appendChild(orderVoid);
        editInfoDiv.appendChild(notes);

        editInfoDiv.visibility = "visible";
    }

    var editButtonsDiv = document.getElementById("editButtonsDiv");

    if (editButtonsDiv.visibility != "visible") {

        var costHeader = document.createElement("h3");
        costHeader.textContent = "Order Total: ";

        var totalCost = document.createElement("input");
        totalCost.type = "number";
        totalCost.id = "etotalCost";
        totalCost.step = ".01";
        totalCost.placeholder = "Order Total";
        totalCost.name = "etotalCost";

        var finalizeButton = document.createElement("input");
        finalizeButton.type = "submit";
        finalizeButton.value = "Finalize Item Selection";
        finalizeButton.name = "submit";

        editButtonsDiv.append(costHeader);
        editButtonsDiv.append(totalCost);
        editButtonsDiv.append(document.createElement("br"));
        editButtonsDiv.append(document.createElement("br"));
        editButtonsDiv.append(finalizeButton);

        editButtonsDiv.visibility = "visible";
    }
}

function editVendors()
{
    createCookie("vendorID",document.getElementById("vendorID").value, "0.25");
    var vendors = [];
    returnAjax("../../Back End/editVendor.php", function(data) {
        //console.log(data); // debug
        for (var i = 0; i < data.length-1; i++) {
            vendors.push([data[i].vendorName, data[i].vendorAddress, data[i].vendorCity, data[i].vendorState, data[i].vendorZip, data[i].vendorPhone, data[i].vendorEmail]);
            document.getElementById("evendName").value = data[i].vendorName;
            document.getElementById("evendAddress").value = data[i].vendorAddress;
            document.getElementById("evendCity").value = data[i].vendorCity;
            document.getElementById("evendState").value = data[i].vendorState;
            document.getElementById("evendZip").value = data[i].vendorZip;
            document.getElementById("evendPhone").value = data[i].vendorPhone;
            document.getElementById("evendEmail").value = data[i].vendorEmail;
        }

    }, true);
    var form = document.getElementById("vendEdit");

    form.style.display = "block";
}

function eitemTotal()
{
    var price = document.getElementsByName('eitems[]uPrice');
    var quantity = document.getElementsByName('eitems[]oQuantity');
    var total_Price = 0;

    for (var i = 0; i < price.length; i++) {
        var a = price[i].value;
        var b = quantity[i].value;
        total_Price = parseFloat(a)*parseFloat(b);

        var divobj = document.getElementsByName('eitems[]tPrice');
        divobj[i].value = total_Price.toFixed(2);
    }
}

function eorderTotal()
{
    var price = document.getElementsByName('eitems[]tPrice');
    var quantity = document.getElementsByName('eitems[]oQuantity');
    var total_Price = 0;

    for (var i = 0; i < price.length; i++) {
        var a = price[i].value;
        //var b = quantity[i].value;
        total_Price += (parseFloat(a));

        var divobj = document.getElementById('etotalCost');
        divobj.value = total_Price.toFixed(2);
    }
}


function itemTotal()
{
    var price = document.getElementsByName('items[]uPrice');
    var quantity = document.getElementsByName('items[]oQuantity');
    var total_Price = 0;

    for (var i = 0; i < price.length; i++) {
        var a = price[i].value;
        var b = quantity[i].value;
        total_Price = parseFloat(a)*parseFloat(b);

        var divobj = document.getElementsByName('items[]tPrice');
        divobj[i].value = total_Price.toFixed(2);
    }
}

function orderTotal()
{
    var price = document.getElementsByName('items[]tPrice');
    var quantity = document.getElementsByName('items[]oQuantity');
    var total_Price = 0;

    for (var i = 0; i < price.length; i++) {
        var a = price[i].value;
        //var b = quantity[i].value;
        total_Price += (parseFloat(a));

        var divobj = document.getElementById('totalCost');
        divobj.value = total_Price.toFixed(2);
    }
}

function getItems() {
    function newItems(called = false) { // for add item button
        if (called == true) {
            newDiv = document.createElement("div");

            var contractNumber = document.createElement("input");
            contractNumber.type = "number";
            contractNumber.placeholder = "Contract Number";
            contractNumber.name = "items[]cNumber";

            var itemName = document.createElement("input");
            itemName.type = "text";
            itemName.placeholder = "Item Name";
            itemName.name = "items[]iName";

            var desc = document.createElement("input");
            desc.type = "text";
            desc.placeholder = "Item Description";
            desc.name = "items[]oDescription";

            var quantity = document.createElement("input");
            quantity.id = "quantity";
            quantity.type = "number";
            quantity.step = "1";
            quantity.value = "1";
            quantity.min = "1";
            quantity.placeholder = "Quantity";
            quantity.name = "items[]oQuantity";
            quantity.oninput = function() {itemTotal(), orderTotal()};

            var price = document.createElement("input");
            price.id = "price";
            price.type = "number";
            price.min = "1";
            price.step = ".01";
            price.placeholder = "Price";
            price.name = "items[]uPrice";
            price.oninput = function() {itemTotal(), orderTotal()};

            var totalPrice = document.createElement("input");
            totalPrice.id = "totalPrice";
            totalPrice.type = "number";
            totalPrice.step = ".01"
            totalPrice.placeholder = "Total Price";
            totalPrice.name = "items[]tPrice";

            var space = document.createElement("br");

            var removeItem = document.createElement("button");
            removeItem.type = "button";
            removeItem.textContent = "Remove Item";
            removeItem.onclick = function () {deleteItem(contractNumber,itemName,desc,quantity,price,totalPrice, removeItem, space), itemTotal(), orderTotal()};

            newDiv.appendChild(contractNumber);
            newDiv.appendChild(itemName);
            newDiv.appendChild(desc);
            newDiv.appendChild(quantity);
            newDiv.appendChild(price);
            newDiv.appendChild(totalPrice);
            newDiv.appendChild(removeItem);
            newDiv.appendChild(space);

            itemDiv.append(newDiv);
            itemDiv.style.visibility = "visible";
        }
    }

    var infoDiv = document.getElementById("infoDiv");
    var itemDiv = document.getElementById("itemDiv");
    //var liDiv = document.createElement("div");

    if (infoDiv.visibility != "visible") {
        var userName = document.createElement("input");
        userName.type = "text";
        userName.placeholder = "Username";
        userName.id = "user_name";
        userName.name = "uLogin";


        var utc = new Date().toJSON().slice(0,10);
        console.log(utc)

        var orderDate = document.createElement("input");
        orderDate.type = "date";
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
        voidLabel.textContent = " Order Void: "


        var notes = document.createElement("input");
        notes.type = "text";
        notes.placeholder = "Notes";
        notes.name = "note";

        infoDiv.appendChild(userName);
        infoDiv.appendChild(orderDate);
        infoDiv.appendChild(orderLabel);
        infoDiv.appendChild(orderComplete);
        infoDiv.appendChild(voidLabel);
        infoDiv.appendChild(orderVoid);
        infoDiv.appendChild(notes);


        infoDiv.visibility = "visible";
    }


    var buttonsDiv = document.getElementById("buttonsDiv");

    if (buttonsDiv.visibility != "visible") {

        var costHeader = document.createElement("h3");
        costHeader.textContent = "Order Total: ";

        var totalCost = document.createElement("input");
        totalCost.type = "number";
        totalCost.id = "totalCost";
        totalCost.step = ".01";
        totalCost.placeholder = "Order Total";
        totalCost.name = "totalCost";

        var checkCount = 2;
        var email = document.createElement("input");
        email.type = "checkbox";
        email.id = "email";
        email.name = "email";
        email.onclick = function () {enteremail(checkCount++)};

        var enterEmail = document.createElement("input");
        enterEmail.type = "email";
        enterEmail.id = "enterEmail";
        enterEmail.name = "enterEmail";
        enterEmail.placeholder = "Enter Email";
        enterEmail.style.display = "none";

        var emailLabel = document.createElement("span");
        emailLabel.textContent = " I would like to receive an email confirming my order. ";

        var addItemButton = document.createElement("button");
        addItemButton.type = "button";
        addItemButton.textContent = "Click to add new item";
        addItemButton.onclick = function() {newItems(true)};

        var finalizeButton = document.createElement("input");
        finalizeButton.type = "submit";
        finalizeButton.value = "Finalize Item Selection";
        finalizeButton.name = "submit";

        buttonsDiv.append(costHeader);
        buttonsDiv.append(totalCost);
        buttonsDiv.append(document.createElement("br"));
        buttonsDiv.append(document.createElement("br"));
        buttonsDiv.append(emailLabel);
        buttonsDiv.append(email);
        buttonsDiv.append(enterEmail);
        buttonsDiv.append(document.createElement("br"));
        buttonsDiv.append(document.createElement("br"));
        buttonsDiv.append(addItemButton);
        buttonsDiv.append(finalizeButton);

        buttonsDiv.visibility = "visible";
    }
}

function deleteItem(contract, itemname, itemdesc,qty,price, total, remove, space)
{
    contract.remove();
    itemname.remove();
    itemdesc.remove();
    qty.remove();
    price.remove();
    total.remove();
    remove.remove();
    space.remove();
}

//hides and un-hides the email input
function enteremail(checkCount)
{
    //var div = document.getElementById("buttonsDiv");
    var enterEmail = document.getElementById("enterEmail");
    if(checkCount%2===0)
    {
        enterEmail.style.display = "block";
    }else if(checkCount%2!==0){enterEmail.style.display = "none";}
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
/*
    // validate Email Address ending
    vMailSuffix = vMail.value.substr(vMail.value.length - 4);
    for (var i = 0; i < validEmailSuffixes.length; i++){
        if (vMailSuffix === validEmailSuffixes[i]){
            break
        }
    }
    if (i === validEmailSuffixes.length) {
        warn.textContent = "Invalid Email Address!";
        warn.style.visibility = "visible";
        return false;
    }

    // validate @ symbol present in email
    for (var i = 0; i < vMail.value.length; i++){
        if (vMail.value[i] === '@'){
            break
        }
    }
    if (i == vMail.value.length) {
        warn.textContent = "Invalid Email Address!";
        warn.style.visibility = "visible";
        return false;
    }
*/
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