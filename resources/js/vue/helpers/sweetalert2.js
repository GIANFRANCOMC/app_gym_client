function showLoading(titulo = "") {

    Swal.fire({
        html: `<h5>${titulo} Este proceso puede tomar algunos momentos, por favor espere.</h5>
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

function errorSwal({titulo = "Error", descripcion = ""}){

    Swal.fire(titulo, descripcion, "error");

}

function mensajeEliminar(mensaje) {

    return `Al realizar esta acción se eliminará ${mensaje}. No podrá revertir la acción.`;

}

export { showLoading, hideSwal, successSwal, errorSwal, mensajeEliminar };
