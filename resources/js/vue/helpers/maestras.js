import { requestRoute } from "./constants.js";

import axios from "axios";

async function sexos() {

    let arraySexos = [
        { "id": "hombre", "value": "Hombre" },
        { "id": "mujer", "value": "Mujer" },
    ];

    return new Promise(resolve => {

        resolve(arraySexos);

    });

}

async function gradoInstrucciones() {

    let arrayGradoInstrucciones = [
        { "id": "primariaIncompleta",   "value": "Primaria incompleta" },
        { "id": "primariaCompleta",     "value": "Primaria completa" },
        { "id": "secundariaIncompleta", "value": "Secundaria incompleta" },
        { "id": "secundariaCompleta",   "value": "Secundaria completa" },
        { "id": "superiorIncompleta",   "value": "Superior incompleta" },
        { "id": "superiorCompleta",     "value": "Superior completa" },
    ];

    return new Promise(resolve => {

        resolve(arrayGradoInstrucciones);

    });

}

async function paises() {

    return new Promise(resolve => {

        let requestUrl = `${requestRoute}/pais/select`;

        axios
        .get(requestUrl)
        .then((response) => {

            resolve(response.data);

        })
        .catch((error) => {

            resolve(false);

        })
        .finally(function () {

        });

    });

}

async function estadosCiviles() {

    let arrayEstadosCiviles = [
        { "id": "s" , "value": "Soltero" },
        { "id": "c" , "value": "Casado" },
        { "id": "d" , "value": "Divorciado" },
        { "id": "v" , "value": "Viudo" }
    ];

    return new Promise(resolve => {

        resolve(arrayEstadosCiviles);

    });

}

async function gestionDocumentoTipos() {

    let arrayGestionDocumentoTipos = [
        { "id": "dni", "value": "DNI" },
        { "id": "partida_nacimiento", "value": "Partida de nacimiento" },
        { "id": "pasaporte", "value": "Pasaporte" },
        { "id": "otro", "value": "Otro" }
    ];

    return new Promise(resolve => {

        resolve(arrayGestionDocumentoTipos);

    });

}

async function docentes() {

    return new Promise(resolve => {

        let requestUrl = `${requestRoute}/docente/select`;

        axios
        .get(requestUrl)
        .then((response) => {

            resolve(response.data);

        })
        .catch((error) => {

            resolve(false);

        })
        .finally(function () {


        });

    });

}

async function modulos() {

    return new Promise(resolve => {

        let requestUrl = `${requestRoute}/modulo/select`;

        axios
        .get(requestUrl)
        .then((response) => {

            resolve(response.data);

        })
        .catch((error) => {

            resolve(false);

        })
        .finally(function () {


        });

    });

}

async function textoBooleanos() {

    let arrayTextoBooleanos = [
        { "id": "si" , "value": "Sí" },
        { "id": "no" , "value": "No" }
    ];

    return new Promise(resolve => {

        resolve(arrayTextoBooleanos);

    });

}

async function diasSemana() {

    let arrayDiasSemana = [
        { "id": 1, "value": "Lunes" },
        { "id": 2, "value": "Martes" },
        { "id": 3, "value": "Miércoles" },
        { "id": 4, "value": "Jueves" },
        { "id": 5, "value": "Viernes" },
        { "id": 6, "value": "Sábado" },
        { "id": 7, "value": "Domingo" }
    ];

    return new Promise(resolve => {

        resolve(arrayDiasSemana);

    });

}

async function secciones() {

    let arraySecciones = [
        { "id": "A", "value": "A" },
        { "id": "B", "value": "B" },
        { "id": "C", "value": "C" },
        { "id": "D", "value": "D" },
        { "id": "E", "value": "E" },
        { "id": "F", "value": "F" },
        { "id": "G", "value": "G" },
        { "id": "H", "value": "H" },
    ];

    return new Promise(resolve => {

        resolve(arraySecciones);

    });

}

async function criterioEvaluaciones(aperturaModuloId = 0) {

    return new Promise(resolve => {

        let requestUrl = `${requestRoute}/criterioEvaluacion/select/${aperturaModuloId}`;

        axios
        .get(requestUrl)
        .then((response) => {

            resolve(response.data);

        })
        .catch((error) => {

            resolve(false);

        })
        .finally(function () {

        });

    });

}


export { sexos, gradoInstrucciones, paises, estadosCiviles, gestionDocumentoTipos, docentes, modulos, textoBooleanos, diasSemana, secciones, criterioEvaluaciones };
