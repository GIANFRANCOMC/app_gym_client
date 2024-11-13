import { requestRoute, generalConfig } from "./Constants.js";
import { toastrs } from "./Alerts.js";

import axios from "axios";

export function navbarItem(id, {type = "active", addClass = null}) {

    try {

        document.getElementById(id).classList.add(type);

        if(isDefined({value: addClass})) {

            document.getElementById(id).classList.add(addClass);

        }

    }catch(e) {

    }

}

export function isDefined({value}) {

    return value != "" && value != null && value != undefined;

}

export function consultDocumentNumber({numberDocument, type, withAlert = true}) {

    let responseData = {};
    responseData.bool    = false;
    responseData.message = "";
    responseData.data    = {};

    return new Promise(resolve => {

        let requestUrl    = `${requestRoute}/helpers/consultDocumentNumber`,
            requestConfig = {};

        let params = {};
        params.document_number = numberDocument;
        params.type            = type;

        requestConfig.params = params;

        axios
        .get(requestUrl, requestConfig)
        .then((response) => {

            switch(response.status) {
                case 200:
                    if(response.data.success) {

                        let data = response.data.data;

                        responseData.bool    = true;
                        responseData.message = "La información que buscabas ha sido encontrada.";
                        responseData.data    = {document_number: `${data?.numero}`, last_name: `${data?.apellido_paterno} ${data?.apellido_materno}`, first_name: `${data?.nombres}`};

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
        .finally(() => {

            if(withAlert) responseData.bool ? toastrs({subtitle: responseData.message, type: "success"}) : toastrs({subtitle: responseData.message, type: "warning"});

            resolve(responseData);

        });

    });

}

export function uuid(length = 36) {

    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c => (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)).substr(0, length);

}

export function getCurrentDate() {

    // Obtener la fecha actual
    const currentDate = new Date();

    // Obtener el año actual
    const currentYear = currentDate.getFullYear();

    // Obtener el mes actual (agregando un cero adelante si es necesario)
    const currentMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);

    // Obtener el día del mes actual (agregando un cero adelante si es necesario)
    const currentDay = ('0' + currentDate.getDate()).slice(-2);

    // Construir la fecha en formato "YYYY-MM-DD"
    const formattedDate = `${currentYear}-${currentMonth}-${currentDay}`;

    return formattedDate;

}

export function cloneJson(json) {

    return JSON.parse(JSON.stringify(json));

}

export function calculateTotal({item}) {

    const quantity = Number(item?.quantity),
          price    = Number(item?.price);

    const total = (isNaN(quantity) || isNaN(price)) ? 0 : this.fixedNumber(quantity * price);

    return total;

}

export function fixedNumber(value) {

    return Number(value).toFixed(generalConfig.forms.inputs.round);

}

export function truncate({value, length = 40}) {

    if(!value) return "";
    return value.length > length ? value.slice(0, length) + "..." : value;

}
