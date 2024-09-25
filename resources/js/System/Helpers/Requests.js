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
                store: `${requestRoute}/items`,
                update: `${requestRoute}/items`,
                initParams: `${requestRoute}/items/initParams`,
            }
        };

        if(Utils.isDefined({value: type})) {

            return config?.routes[type];

        }else {

            return config;

        }

    }else if(entity === "sales") {

        let config = {
            session: {
                default: "sale_id"
            },
            routes: {
                consult: `${requestRoute}/sales`,
                list: `${requestRoute}/sales/list`,
                get: `${requestRoute}/sales/get`,
                store: `${requestRoute}/sales`,
                update: `${requestRoute}/sales`,
                initParams: `${requestRoute}/sales/initParams`,
            }
        };

        if(Utils.isDefined({value: type})) {

            return config?.routes[type];

        }else {

            return config;

        }

    }else if(entity === "users") {

        let config = {
            session: {
                default: "user_id"
            },
            routes: {
                consult: `${requestRoute}/users`,
                list: `${requestRoute}/users/list`,
                get: `${requestRoute}/users/get`,
                store: `${requestRoute}/users`,
                update: `${requestRoute}/users`,
                initParams: `${requestRoute}/users/initParams`,
            }
        };

        if(Utils.isDefined({value: type})) {

            return config?.routes[type];

        }else {

            return config;

        }

    }else if(entity === "customers") {

        let config = {
            session: {
                default: "customer_id"
            },
            routes: {
                consult: `${requestRoute}/customers`,
                list: `${requestRoute}/customers/list`,
                get: `${requestRoute}/customers/get`,
                store: `${requestRoute}/customers`,
                update: `${requestRoute}/customers`,
                initParams: `${requestRoute}/customers/initParams`,
            }
        };

        if(Utils.isDefined({value: type})) {

            return config?.routes[type];

        }else {

            return config;

        }

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

		axios
		.post(requestURL, requestData)
		.then(response => {

			resolve({data: response.data, bool: true});

		})
		.catch(error => {

            if([405].includes(error?.response?.status)) {

                resolve({data: {msg: error?.response?.data?.message}, bool: false});

            }else if([422].includes(error?.response?.status)) {

                resolve({data: {msg: error?.response?.data?.message}, errors: error?.response?.data?.errors, bool: false});

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

		axios
		.patch(requestURL, requestData)
		.then(response => {

			resolve({data: response.data, bool: true});

		})
		.catch(error => {

            if([405].includes(error?.response?.status)) {

                resolve({data: {msg: error?.response?.data?.message}, bool: false});

            }else if([422].includes(error?.response?.status)) {

                resolve({data: {msg: error?.response?.data?.message}, errors: error?.response?.data?.errors, bool: false});

            }else {

                resolve({data: {msg: error},  bool: false});

            }

		})
		.finally(() => {

			Alerts.tooltips({});

		});

	});

}
