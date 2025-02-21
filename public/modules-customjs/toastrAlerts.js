/**
 * Toastr Alerts
 **/

toastrPosition = (position) => {
    if(position == ""){
        position = "toast-top-right";
    }else if(position == "left") {
        position = "toast-top-left";
    }else if(position == "right") {
        position = "toast-top-right";
    }else if(position == "right") {
        position = "toast-top-right";
    }else if(position == "center") {
        position = "toast-top-center";
    }else{
        position = "toast-top-right";
    }
    toastr.options = {
        "closeButton": false,
        "debug": true,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": position,
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}
/**
 *
 * Success
 * @param message
 */
successAlert = (message) => {

    toastr["success"](message)
}
successLeftAlert = (message) => {

    toastrPosition("left");
    toastr["success"](message)
}
successCenterAlert = (message) => {

    toastrPosition("center");
    toastr["success"](message)
}
/**
 * warning
 * @param message
 */
warningAlert = (message) => {
    toastr["warning"](message)
}
warningLeftAlert = (message) => {

    toastrPosition("left");
    toastr["warning"](message)
}
warningCenterAlert = (message) => {

    toastrPosition("center");
    toastr["warning"](message)
}
/**
 * Danger
 * @param message
 */
errorAlert = (message) => {
    toastr["error"](message)
}
errorLeftAlert = (message) => {

    toastrPosition("left");
    toastr["error"](message)
}
errorCenterAlert = (message) => {

    toastrPosition("center");
    toastr["error"](message)
}
/**
 * Info
 * @param message
 */
infoAlert = (message) => {
    toastr["info"](message)
}
infoLeftAlert = (message) => {

    toastrPosition("left");
    toastr["info"](message)
}
infoCenterAlert = (message) => {

    toastrPosition("center");
    toastr["info"](message)
}
