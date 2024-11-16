import axios from "axios";
import * as Alerts from "./Alerts.js";
import * as Utils from "./Utils.js";
import { requestRoute, generalConfig } from "./Constants.js";

export function route() {

    return `${requestRoute}`;

}

export function config({entity = "", type = ""}) {

    let requestRoute = this.route({});

    let config = {
        routes: this.generateRoutes({entity, requestRoute})
    };

    if(entity === "exports") {

        config = {
            routes: {
                default: `${requestRoute}/exports`
            }
        };

    }

    if(Utils.isDefined({value: type})) {

        return config?.routes[type];

    }else {

        return config;

    }

}


export function get({route = "", data = {}, showAlert = false}) {

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

            if(showAlert) {

                if([500].includes(error?.response?.status)) {

                    Alerts.toastrs({type: "error", subtitle: error?.response?.data?.message});

                }else {

                    Alerts.toastrs({type: "error", subtitle: error});

                }

            }

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

        if(!Utils.isDefined({value: requestData?.id})) {

            delete requestData.id;

        }

		axios
		.post(requestURL, requestData)
		.then(response => {

			resolve({data: response.data, bool: true});

		})
		.catch(error => {

            if([405].includes(error?.response?.status)) {

                resolve({data: {msg: `${error?.response?.data?.message} (Code ${error?.response?.status})`}, bool: false});

            }else if([422].includes(error?.response?.status)) {

                resolve({data: {msg: `${error?.response?.data?.message} (Code ${error?.response?.status})`}, errors: error?.response?.data?.errors, bool: false});

            }else {

                resolve({data: {msg: error},  bool: false});

            }

		})
		.finally(() => {

			Alerts.tooltips({});

		});

	});

}

export function patch({route = "", data = {}, id = "", formData = null}) {

	return new Promise((resolve, reject) => {

		let requestURL  = `${route}/${id}`,
			requestData = formData ?? {...data, id};

        if(!Utils.isDefined({value: requestData?.id})) {

            delete requestData.id;

        }

		axios
		.patch(requestURL, requestData)
		.then(response => {

			resolve({data: response.data, bool: true});

		})
		.catch(error => {

            if([405].includes(error?.response?.status)) {

                resolve({data: {msg: `${error?.response?.data?.message} (Code ${error?.response?.status})`}, bool: false});

            }else if([422].includes(error?.response?.status)) {

                resolve({data: {msg: `${error?.response?.data?.message} (Code ${error?.response?.status})`}, errors: error?.response?.data?.errors, bool: false});

            }else {

                resolve({data: {msg: error},  bool: false});

            }

		})
		.finally(() => {

			Alerts.tooltips({});

		});

	});

}

export function valid({result}) {

    return result?.bool && result?.data?.bool;

}

export function generateRoutes({entity, requestRoute}) {

    let routes = {
        consult: `${requestRoute}/${entity}`,
        list: `${requestRoute}/${entity}/list`,
        get: `${requestRoute}/${entity}/get`,
        create: `${requestRoute}/${entity}/create`,
        store: `${requestRoute}/${entity}`,
        update: `${requestRoute}/${entity}`,
        initParams: `${requestRoute}/${entity}/initParams`,
    };

    if(["sales"].includes(entity)) {

        routes.cancel = `${requestRoute}/${entity}/cancel`;

    }

    return routes;

}
