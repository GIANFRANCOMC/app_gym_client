import * as Utils from "./Utils.js";

export const requestRoute = `${window.location.protocol}//${window.location.hostname}`;

export const generalConfig = {
    project: {
        company: "BLAPOS"
    },
    essential: Utils.getEssential(),
    messages: {
        withoutResults: "Sin registros",
        errorValidate: "Error al validar, revisar el formulario."
    },
    forms: {
        classes: {
            title: "fw-bold colon-at-end fs-6",
            select2: "bg-white"
        },
        inputs: {
            maxlength: 999,
            required: "*",
            round: 2,
            minValue: 0,
            maxValue: 9999999
        },
        errors: {
            functions: {
                beetwen: {
                    numeric: (min = "", max = "") => `Debe estar entre ${min} y ${max}`
                },
                min: {
                    numeric: (min = "") => `Debe ser al menos ${min}`
                },
                max: {
                    numeric: (min = "") => `No debe ser mayor que ${min}`
                }
            },
            labels: {
                required: "Es obligatorio",
                min_number_0: "Debe ser mayor a 0",
                min_equal_number_0: "Debe ser mayor o igual a 0"
            },
            styles: {
                default: "text-danger"
            }
        }
    },
    colors: {
        charts: {
            default: {
                backgroundColor: "#ffffff",
                bodyColor: "#6f6b7d",
                labelColor: "#7a7883",
                borderColor: "#dbdade",
                titleColor: "#5d596c",
                defaultColor: "#1da1f2",
                primaryColor: "#7367F0",
                successColor: "#28C76F",
                dangerColor: "#EA5455"
            }
        }
    }
};
