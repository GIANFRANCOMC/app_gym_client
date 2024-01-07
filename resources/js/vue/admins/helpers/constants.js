const requestRoute = `${window.location.protocol}//${window.location.hostname}/public`;

const generalConfiguration = {
    messages: {
        withoutResults: "Sin resultados"
    },
    forms: {
        inputs: {
            maxlength: 999
        }
    }
};

export { requestRoute, generalConfiguration };
