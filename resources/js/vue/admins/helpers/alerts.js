export function swals({show = true, type = null, title = ""}) {

    if(show) {

        let message = "";

        switch(type) {
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
                   <img src='../../../../public/assets/img/utils/spin.gif' width='80'>`,
            allowOutsideClick: false,
            showConfirmButton: false
        });

    }else {

        Swal.close();

    }

}

export function toastrs({title = null, subtitle = null, code = null, type = "success", options = null}) {

    let toastrOptions = {};

    if(!title) {

        switch(type) {
            case "error":
                title = "Algo salió mal";
                break;

            case "success":
                title = "Completado con éxito";
                break;

            case "warning":
                title = "Atención";
                break;
        }

    }

    if(!subtitle) {

        switch(type) {
            case "error":
                if(code) {

                    switch(code) {
                        case 422:
                            subtitle = "Corrige los errores del formulario e intenta de nuevo.";
                            break;
                    }

                }
                break;

            case "success":
                subtitle = "";
                break;

            case "warning":
                subtitle = "";
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
