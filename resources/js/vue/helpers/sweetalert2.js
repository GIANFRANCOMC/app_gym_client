function mostrarCargando(titulo = "") {

    Swal.fire({
        html: `<h5>${titulo} Este proceso puede tomar algunos momentos, por favor espere.</h5>
               <img src='../../../../public/img/spin.gif' width='80'>`,
        allowOutsideClick: false,
        showConfirmButton: false
    });

}

function ocultarSwal(){

	Swal.close();

}

function successSwal({titulo = "Se efectuó correctamente", descripcion = ""}){

    Swal.fire(titulo, descripcion, "success");

}

function errorSwal({titulo = "Error", descripcion = ""}){

    Swal.fire(titulo, descripcion, "error");

}

function mensajeEliminar(mensaje){

    return `Al realizar esta acción se eliminará ${mensaje}. No podrá revertir la acción.`;

}

export { mostrarCargando, ocultarSwal, successSwal, errorSwal, mensajeEliminar };
