import { requestRoute } from "./constants.js";
import { toastrAlert } from "./alerts.js";

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

function simularClickTabPanel(id) {

    $(`.nav-tabs a[href="#${id}"]`).tab('show');

}

function parsePadStart(string, {length = 2, valuePad = "0"}) {

    let respuesta = "";

    respuesta = string.toString().padStart(length, valuePad);

    return respuesta;

}

function validateVariable({value}) {

    return value != "" && value != null && value != undefined;

}

function consultNumberDocument({numberDocument, type, withAlert = true}) {

    let responseData = {};
    responseData.bool    = false;
    responseData.message = "";
    responseData.data    = {};

    return new Promise(resolve => {

        let requestUrl    = `${requestRoute}/helpers/consultNumberDocument`,
            requestConfig = {};

        requestConfig.params = {};
        requestConfig.params.number_document = numberDocument;
        requestConfig.params.type            = type;

        axios
        .get(requestUrl, requestConfig)
        .then((response) => {

            switch(response.status) {
                case 200:
                    if(response.data.success) {

                        let data = response.data.data;

                        responseData.bool    = true;
                        responseData.message = "La información que buscabas ha sido encontrada.";
                        responseData.data    = {number_document: `${data?.numero}`, last_name: `${data?.apellido_paterno} ${data?.apellido_materno}`, first_name: `${data?.nombres}`};

                    }else {

                        responseData.bool    = false;
                        responseData.message = response.data.message;

                    }
                    break;
            }

        })
        .catch((error) => {

            responseData.bool    = false;
            responseData.message = error;

        })
        .finally(async() => {

            if(withAlert) await (responseData.bool ? toastrAlert({subtitle: responseData.message, type: "success"}) : toastrAlert({subtitle: responseData.message, type: "warning"}));

            resolve(responseData);

        });

    });

}


export { headerFormData, borrarSessionStorage, uuidv4, encontarArrayObjectParametro, convertirObjectoAFormData,
         descargarFile, procesarErroresFilasMultiples, obtenerValueSelect, obtenerArrayValueSelect, obtenerMapValueSelect,
         convertirDateBackToFront, convertirDateTimeBackToFront,
         simularClickTabPanel, parsePadStart, consultNumberDocument, validateVariable };
