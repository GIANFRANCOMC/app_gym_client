export const requestRoute = `${window.location.protocol}//${window.location.hostname}`;

export const generalConfig = {
    messages: {
        withoutResults: "Sin resultados"
    },
    forms: {
        inputs: {
            maxlength: 999,
            required: "*",
            round: 2
        },
        errors: {
            labels: {
                required: "Es obligatorio",
                min_number_0: "Debe ser mayor a 0"
            },
            styles: {
                default: "text-danger"
            }
        }
    }
};
