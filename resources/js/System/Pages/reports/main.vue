<template>
    <Breadcrumb :list="[config.entity.page]"/>

    <div class="row row align-items-end g-3 mb-4">
        <InputSlot
            hasDiv
            title="Reporte"
            :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
            xl="6"
            lg="6">
            <template v-slot:input>
                <v-select
                    v-model="forms.entity.createUpdate.data.report"
                    :options="reports"
                    :class="'bg-white'"
                    :clearable="true"/>
            </template>
        </InputSlot>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            xl="3"
            lg="6">
            <template v-slot:input>
                <button class="btn btn-primary waves-effect" type="button" @click="exportReport({})">
                    <i class="fa fa-search"></i>
                    <span class="ms-1">Buscar</span>
                </button>
            </template>
        </InputSlot>
    </div>
</template>

<script>
import axios from "axios";
import * as Alerts    from "../../Helpers/Alerts.js";
import * as Constants from "../../Helpers/Constants.js";
import * as Requests  from "../../Helpers/Requests.js";
import * as Utils     from "../../Helpers/Utils.js";

export default {
    components: {
        //
    },
    mounted: async function() {

        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});

        }

    },
    data() {
        return {
            forms: {
                entity: {
                    createUpdate: {
                        data: {
                            report: null,
                            branches: {

                            },
                            items: {

                            },
                            customers: {

                            },
                            sales: {

                            },
                            users: {

                            },
                        },
                        errors: {}
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "reports"}),
                    page: {
                        title: "Reportes",
                        active: true,
                        menu: {
                            id: "menu-item-reports"
                        }
                    }
                }
            }
        };
    },
    methods: {
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, showAlert: true});

            this.options.customers             = initParams.data?.config?.customers;
            this.options.identityDocumentTypes = initParams.data?.config?.identityDocumentTypes;

            return initParams?.bool && initParams?.data?.bool;

        },
        async initOthers({}) {

            return true;

        },
        // Forms
        async exportReport({}) {

            const functionName = "exportReport";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let report = this.forms.entity.createUpdate.data.report;

            let form = JSON.parse(JSON.stringify(this.forms.entity.createUpdate.data[report?.code]));

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                try {

                    const response = await axios.get(this.config.entity.routes.consult+`/${report?.code}`, {
                    responseType: 'blob', // Importante para manejar archivos
                    });

                    // Crear un enlace temporal para descargar el archivo
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'reporte.xlsx'); // Nombre del archivo
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    Alerts.swals({show: false});
                } catch (error) {
                    console.error("Error descargando el archivo:", error);
                    Alerts.swals({show: false});
                }

            }else {

                this.formErrors({functionName, type: "set", errors: validateForm});
                Alerts.swals({show: false});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.id              = null;
                    this.forms.entity.createUpdate.data.identity_document_type_id   = "";
                    this.forms.entity.createUpdate.data.document_number = "";
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["modalCreateUpdateEntity", "createUpdateEntity"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null}) {

            let result = {
                bool: true
            };

            if(["createUpdateEntity"].includes(functionName)) {

                result.identity_document_type_id = [];

                // if(!this.isDefined({value: form?.identity_document_type_id})) {

                    // result.identity_document_type_id.push(this.config.forms.errors.labels.required);
                    // result.bool = false;

                // }

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        }
    },
    computed: {
        reports: function() {

            return [
                {code: "customers", label: "Clientes"},
                {code: "users", label: "Colaboradores"},
                {code: "items", label: "Productos - Servicios"},
                {code: "branches", label: "Sucursales"},
                {code: "sales", label: "Ventas"}
            ];

        }
    }
};
</script>
