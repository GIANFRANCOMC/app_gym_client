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

export { showLoading, hideSwal, successSwal, errorSwal, mensajeEliminar };
