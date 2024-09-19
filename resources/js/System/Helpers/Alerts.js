import { requestRoute } from "./Constants.js";

export function swals({show = true, type = "default"}) {

    if(show) {

        let message = "";

        switch(type) {
            case "default":
                message = "Cargando";
                break;

            case "initParams":
                message = "";
                break;

            case "externalConsult":
                message = "Consultando.";
                break;

            case "list":
                message = "Cargando listado.";
                break;

            case "saveForm":
                message = "Guardando formulario.";
                break;
        }

        Swal.fire({
            html: `<h5>${message} Este proceso puede tomar algunos segundos, por favor espere.</h5>
                   <img src='${requestRoute}/System/assets/img/utils/spin.gif' width='80'>`,
            allowOutsideClick: false,
            showConfirmButton: false
        });

    }else {

        Swal.close();

    }

}

export function toastrs({type = "success", options = null, code = null, title = null, subtitle = null}) {

    let toastrOptions = {};

    if(!title) {

        switch(type) {
            case "error":
                title = "¡Ups! Algo salió mal";
                break;

            case "success":
                title = "Exitoso";
                break;

            case "warning":
                title = "Atención";
                break;
        }

    }

    if(!options) {

        toastrOptions = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            showMethod: "slideDown",
            timeOut: 2000
        };

    }

    toastr.options = toastrOptions;
    toastr[type](subtitle ?? "", title ?? "");

}

export function tooltips({show = true, time = 0}) {

    if(show) {

        time > 0 ? setTimeout(() => $('[data-bs-toggle="tooltip"]').tooltip(), time) : $('[data-bs-toggle="tooltip"]').tooltip();

    }else {

        time > 0 ? setTimeout(() => $(".tooltip").tooltip("hide"), time) : $(".tooltip").tooltip("hide");

    }

}

export function modals({type = "show", id = null}) {

    if(["show"].includes(type)) {

        $(`#${id}`).modal("show");

    }else if(["hide"].includes(type)) {

        $(`#${id}`).modal("hide");

    }

}
