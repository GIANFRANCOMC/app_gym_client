import axios from "axios";
import * as Alerts from "./Alerts.js";
import * as Utils from "./Utils.js";
import { requestRoute, generalConfig } from "./Constants.js";

export function route() {

    return `${requestRoute}`;

}

export function config({entity = "", type = ""}) {

    let requestRoute = this.route({});

    if(entity === "items") {

        let config = {
            session: {
                default: "item_id"
            },
            routes: {
                consult: `${requestRoute}/items`,
                list: `${requestRoute}/items/list`,
                get: `${requestRoute}/items/get`,
                store: `${requestRoute}/items/store`,
                update: `${requestRoute}/items/update`,
                initParams: `${requestRoute}/items/initParams`,
            }
        };

        if(Utils.isDefined({value: type})) {

            return config?.routes[type];

        }else {

            return config;

        }

    }

}


export function get({route = "", data = {}}) {

	return new Promise((resolve, reject) => {

		let requestUrl    = route,
            requestConfig = {};

        let params = {...data};

        requestConfig.params = params;

		axios
		.get(requestUrl, requestConfig)
		.then(response => {

			resolve({data: response.data, bool: true});

		})
		.catch(error => {

            Alerts.toastrs({subtitle: error, type: "error"});
			resolve({data: {data: [], msg: error}, bool: false});

		})
		.finally(() => {

			Alerts.tooltips({});

		});

	});

}

export function post({route = "", data = {}, id = "", formData = null}) {

	return new Promise((resolve, reject) => {

		let requestURL  = route,
			requestData = formData ?? {...data, id};

		axios
		.post(requestURL, requestData)
		.then(response => {

			resolve({data: response.data, bool: true});

		})
		.catch(error => {

			resolve({data: {msg: error}, bool: false});

		})
		.finally(() => {

			tooltips({show: true});

		});

	});

}
