import { requestRoute } from "./constants.js";
import { showLoading, hideSwal, successSwal, errorSwal } from "./sweetalert2.js";

import axios from "axios";

let headerFormData = { headers: { "Content-Type": "multipart/form-data" } };

function borrarSessionStorage(param = ""){

    if(param == ""){

    }else{

        sessionStorage.removeItem(param);

    }

}

function uuidv4(longitud = 36){

    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    ).substr(0, longitud);

}

async function encontarArrayObjectParametro(array, propiedad, valor){

    return new Promise(resolve => {

        let iterador = null;

        array.find(function(value, index){

            if(value[propiedad] == valor){
                iterador = index;
                return index;
            }

        })

        resolve(iterador);

    });

}

function convertirObjectoAFormData(object) {

    const formData = new FormData();
    Object.keys(object).forEach(key => formData.append(key, object[key]));
    return formData;

}

function defaultDescripcionResponse(code) {

    let respuesta = "";

    switch(code) {
        case 422:
            respuesta = "Completar correctamente el formulario";
            break;

        case 404:
            respuesta = "No encontrado";
            break;
    }

    return respuesta;

}

function descargarFile(url, nombreDocumentoDescargar = "") {

    let a  = document.createElement('a');
    a.href = url;

    let nombreFileCompleto  = (url).substr((url).lastIndexOf('/') + 1),
        nombreArchivo = nombreFileCompleto;

    if(nombreDocumentoDescargar != ""){

        let informacionFile = nombreFileCompleto.split(".");
        informacionFile[0]  = nombreDocumentoDescargar;
        nombreArchivo       = informacionFile.join(".");

    }

    a.download = nombreArchivo;
    document.body.appendChild(a);

    a.click();
    document.body.removeChild(a);

}

function procesarErroresFilasMultiples(cantidadRegistros, errores, nombreArray, arrayPropiedades, posicionInicialReferencia = 0) {

    let respuesta = [],
        keys      = Object.keys(errores);

    for(var i = 0; i < cantidadRegistros; i++){

        respuesta[i] = {};

    }

    for(let [key, value] of keys.entries()) {

        if(value.indexOf(nombreArray) >= 0) {

            let errorKey = value.split(".");

            if(errorKey.length > 0){

                let indexArray = arrayPropiedades.indexOf(errorKey[2 + posicionInicialReferencia]);

                if(indexArray >= 0){

                    let nombrePropiedad = arrayPropiedades[indexArray];
                    respuesta[errorKey[1]][nombrePropiedad] = errores[value];

                }

            }

        }

    }

    return respuesta;

}

function obtenerValueSelect(opciones, value){

    let respuesta = "";

    for(let opcion of opciones){

        if(opcion.id == value){

            respuesta = opcion.value;

        }

    }

    return respuesta;

}

function obtenerArrayValueSelect(opciones, valueArray) {

    let arraySelect = [];

    try {

        for(const valueSelect of valueArray) {

            arraySelect.push({
                id: valueSelect,
                value: obtenerValueSelect(opciones, valueSelect)
            });

        }

    }catch(e) {

    }

    return arraySelect;

}

function obtenerMapValueSelect(opciones, valueArray, separador = ",") {

    let respuesta = "";

    try {

        respuesta = ((valueArray).map((e) => obtenerValueSelect(opciones, e.id))).join(separador);

    }catch(e) {

    }

    return respuesta;

}

function convertirDateBackToFront(value, {separador = "-", separadorRespuesta = "-"}){

    let respuesta = "";

    try {

        let valueArray = value.split(separador);
        respuesta = `${valueArray[2]}${separadorRespuesta}${valueArray[1]}${separadorRespuesta}${valueArray[0]}`;

    }catch(error) {

        respuesta = "Error";

    }

    return respuesta;

}

function convertirDateTimeBackToFront(value, {separador = "-", separadorRespuesta = "-"}){

    let respuesta = "";

    try {

        let valueDate = new Date(value),
            dia       = (valueDate.getDate()).toString().padStart(2, "0"),
            mes       = ((valueDate.getMonth() + 1)).toString().padStart(2, "0"),
            anio      = valueDate.getFullYear(),
            hora      = (valueDate.getHours()).toString().padStart(2, "0"),
            minutos   = (valueDate.getMinutes()).toString().padStart(2, "0");

        respuesta = `${dia}${separadorRespuesta}${mes}${separadorRespuesta}${anio}`+` ${hora}:${minutos}`;

    }catch(error) {

        respuesta = "Error";

    }

    return respuesta;

}

function nombresCompleto(entidad){

    let respuesta = "";

    try {

        respuesta = (Object.keys(entidad)).length > 0 ? `${entidad.apellidoPaterno} ${entidad.apellidoMaterno} ${entidad.nombre}` : "";

    }catch(error) {

        respuesta = "Error";

    }

    return respuesta;

}

function simularClickTabPanel(id) {

    $(`.nav-tabs a[href="#${id}"]`).tab('show');

}

function parsePadStart(string, {length = 2, valuePad = "0"}) {

    let respuesta = "";

    respuesta = string.toString().padStart(length, valuePad);

    return respuesta;

}

function validateVariable(valor, isStringNull = false) {

    let variableValor = null;

    if(isStringNull) {

        variableValor = (valor == "null") ? null : valor;

    }else {

        variableValor = valor;

    }

    return variableValor != "" && variableValor != null && variableValor != undefined;

}

function obtenerNombreModulo(modulo) {

    let respuesta = "";

    try {

        if((Object.keys(modulo)).length > 0) {

            respuesta = ((modulo.secuenciaInterna) ? `${modulo.parseSecuenciaInterna} - ${modulo.nombre}` : `${modulo.nombre}`);

        }

    }catch(e) {

    }

    return respuesta;

}

function obtenerEstadoLegible(estado, tipo = "default") {

    let respuesta = "";

    try {

        switch(estado) {
            case "activo":
                respuesta = "Activo";
                break;

            case "inactivo":
                respuesta = (tipo == "matricula") ? "Retirado" : "Inactivo";
                break;

            case "eliminado":
                respuesta = "Eliminado";
                break;

            case "finalizado":
                respuesta = "Finalizado";
                break;

            default:
                respuesta = "No encontrado";
                break;
        }

    }catch(e) {

    }

    return respuesta;

}

function obtenerInformacionMatriculas(matriculas) {

    let informacionActivos    = (matriculas).filter((e) => e.estado == "activo"),
        informacionRetirados  = (matriculas).filter((e) => e.estado == "inactivo"),
        informacionEliminados = (matriculas).filter((e) => e.estado == "eliminado");

    return { "activo": informacionActivos, "retirado": informacionRetirados, "eliminado": informacionEliminados };

};

function setFotoDefault(registro, modo = "alumno") {

    let rutaFotoDefault = (modo === "alumno" ? alumnoRutaFotoDefault : alumnoRutaFotoDefault),
        respuesta = rutaFotoDefault;

    if(registro?.rutaFoto) {

        respuesta = registro.rutaFoto;

    }

    return respuesta;

}


export { headerFormData, borrarSessionStorage, uuidv4, encontarArrayObjectParametro, convertirObjectoAFormData,
         defaultDescripcionResponse, descargarFile, procesarErroresFilasMultiples, obtenerValueSelect, obtenerArrayValueSelect, obtenerMapValueSelect,
         convertirDateBackToFront, convertirDateTimeBackToFront, nombresCompleto,
         simularClickTabPanel, parsePadStart,
         validateVariable, obtenerNombreModulo, obtenerEstadoLegible, obtenerInformacionMatriculas,
         setFotoDefault };
