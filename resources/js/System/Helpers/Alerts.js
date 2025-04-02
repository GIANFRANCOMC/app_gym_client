import { requestRoute } from "./Constants.js";

export function swals({show = true, type = "default", timeout = 0}) {

    if(show) {

        let message = "";

        switch(type) {
            case "default":
                message = "Cargando.";
                break;

            case "consult":
                message = "Consultando información.";
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

        timeout > 0 ? setTimeout(() => Swal.close(), timeout) : Swal.close();

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

export function tooltips({show = true, time = 10}) {

    if(show) {

        // time > 0 ? setTimeout(() => $('[data-bs-toggle="tooltip"]').tooltip(), time) : $('[data-bs-toggle="tooltip"]').tooltip();
        time > 0 ? setTimeout(() => $('[data-bs-toggle="tooltip"]').tooltip(), time) : $('[data-bs-toggle="tooltip"]').tooltip();

    }else {

        // time > 0 ? setTimeout(() => $(".tooltip").tooltip("hide"), time) : $(".tooltip").tooltip("hide");
        time > 0 ? setTimeout(() => $(".tooltip").hide(), time) : $(".tooltip").hide();

    }

}

export function modals({type = "show", id = null, timeout = 0}) {

    if(["show"].includes(type)) {

        timeout > 0 ? setTimeout(() => $(`#${id}`).modal("show"), timeout) : $(`#${id}`).modal("show");


    }else if(["hide"].includes(type)) {

        timeout > 0 ? setTimeout(() => $(`#${id}`).modal("hide"), timeout) : $(`#${id}`).modal("hide");

    }

}

export function generateAlert({messages = [], type = "warning", headerTitle = null, msgContent = null}) {

	let tableAlertHtml = messages.length > 0 ? this.generateTableAlert({messages, type}) : "";

	Swal.fire({title             : headerTitle,
               icon              : type,
		       allowOutsideClick : false,
		       allowEscapeKey    : false,
		       html              : `${msgContent ?? ""} <div>${tableAlertHtml}</div>`,
               confirmButtonText: "Entendido",
               customClass: {
                   confirmButton: "btn btn-primary waves-effect"
               }});

}

export function generateTableAlert({messages}) {

	let result = messages.length === 0 ? "" : `
	<table class="table table table-hover table-bordered">
		<thead class="table-light">
			<tr class="text-center align-middle">
				<th class="text-center">N°</th>
				<th class="text-center">Mensaje</th>
			</tr>
		</thead>
		<tbody class="table-border-bottom-0 bg-white">
			${messages.reduce((carry, singleMessage, index)=>carry+/*html*/`
			<tr>
				<td class="text-center">${index + 1}</td>
				<td class="text-start">${singleMessage}</td>
			</tr>
			`, "")}
		</tbody>
	</table>
	`;

	return result;

}
