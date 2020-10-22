function msg() {
    var msg = document.getElementById("myMsg");
    msg.classList.toggle("show");
    var msgL = document.getElementById("msgL");
    msgL.classList.toggle("msgL2");

    var req = document.getElementById("myReq");
    var frL = document.getElementById("frL");

    var not = document.getElementById("myNot");
    var notL = document.getElementById("notL");

    if(not.classList.contains("show")){
        not.classList.toggle("show");
        notL.classList.toggle("notL2");
    }
    if(req.classList.contains("show")){
        req.classList.toggle("show");
        frL.classList.toggle("frL2");
    }
}

function request() {
    var req = document.getElementById("myReq");
    req.classList.toggle("show");
    var frL = document.getElementById("frL");
    frL.classList.toggle("frL2");

    var not = document.getElementById("myNot");
    var notL = document.getElementById("notL");

    var msg = document.getElementById("myMsg");
    var msgL = document.getElementById("msgL");

    if(not.classList.contains("show")){
        not.classList.toggle("show");
        notL.classList.toggle("notL2");
    }
    if(msg.classList.contains("show")){
        msg.classList.toggle("show");
        msgL.classList.toggle("msgL2");
    }
}

function notify() {
    var msg = document.getElementById("myMsg");
    var msgL = document.getElementById("msgL");

    var req = document.getElementById("myReq");
    var frL = document.getElementById("frL");

    var not = document.getElementById("myNot");
    not.classList.toggle("show");
    var notL = document.getElementById("notL");
    notL.classList.toggle("notL2");

    if(msg.classList.contains("show")){
        msg.classList.toggle("show");
        msgL.classList.toggle("msgL2");
    }
    if(req.classList.contains("show")){
        req.classList.toggle("show");
        frL.classList.toggle("frL2");
    }
}