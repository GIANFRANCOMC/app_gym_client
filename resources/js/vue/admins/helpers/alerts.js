import { validateVariable } from "./main";

function showLoading({type = null, title = ""}) {

    let message = "";

    switch(type) {
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

}

function hideSwal() {

	Swal.close();

}

function successSwal({titulo = "Se efectuó correctamente", descripcion = ""}){

    Swal.fire(titulo, descripcion, "success");

}

function errorSwal({type = null, title = ""}){

    Swal.fire(title, type.toString(), "error");

}

function mensajeEliminar(mensaje) {

    return `Al realizar esta acción se eliminará ${mensaje}. No podrá revertir la acción.`;

}

function toastrAlert({title = null, subtitle = null, code = null, type = "success", options = null}) {

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
                if(!code) {

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
            timeOut: 3000
        };

    }

    toastr.options = toastrOptions;
    toastr[type](subtitle ?? "", title ?? "");

}

export { showLoading, hideSwal, successSwal, errorSwal, mensajeEliminar, toastrAlert };
