import { requestRoute } from "./constants.js";

import axios from "axios";

export async function getCountries() {

    return new Promise(resolve => {

        let requestUrl = `${requestRoute}/countries/select`;

        axios
        .get(requestUrl)
        .then((response) => {

            resolve(response.data);

        })
        .catch((error) => {

            resolve(false);

        })
        .finally(() => {

            //

        });

    });

}
