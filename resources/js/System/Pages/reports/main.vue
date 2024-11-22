<template>
    <Breadcrumb :list="[config.entity.page]"/>

    <div class="row align-items-end g-3 mb-3">
        <InputSlot
            hasDiv
            title="Reporte"
            :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
            isRequired
            xl="12"
            lg="12">
            <template v-slot:input>
                <v-select
                    v-model="forms.entity.createUpdate.data.report"
                    :options="reports"
                    :class="'bg-white'"
                    :clearable="false"/>
            </template>
        </InputSlot>
    </div>
    <div class="row align-items-end g-3 mb-3">
        <template v-if="isDefined({value: forms.entity.createUpdate.data.report?.code})">
            <template v-if="['customers'].includes(forms.entity.createUpdate.data.report?.code)">
                <InputText
                    v-model="forms.entity.createUpdate.data.customers.document_number"
                    hasDiv
                    title="Número de documento"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    xl="3"
                    lg="6"/>
                <InputText
                    v-model="forms.entity.createUpdate.data.customers.name"
                    hasDiv
                    title="Nombre"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    xl="3"
                    lg="6"/>
            </template>
            <template v-else-if="['users'].includes(forms.entity.createUpdate.data.report?.code)">
                <InputText
                    v-model="forms.entity.createUpdate.data.users.document_number"
                    hasDiv
                    title="Número de documento"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    xl="3"
                    lg="6"/>
                <InputText
                    v-model="forms.entity.createUpdate.data.users.name"
                    hasDiv
                    title="Nombre"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    xl="3"
                    lg="6"/>
            </template>
            <template v-else-if="['items'].includes(forms.entity.createUpdate.data.report?.code)">
                <InputText
                    v-model="forms.entity.createUpdate.data.items.name"
                    hasDiv
                    title="Nombre"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    xl="3"
                    lg="6"/>
                <InputText
                    v-model="forms.entity.createUpdate.data.items.name"
                    hasDiv
                    title="Descripción"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    xl="3"
                    lg="6"/>
            </template>
            <template v-else-if="['branches'].includes(forms.entity.createUpdate.data.report?.code)">
                <InputText
                    v-model="forms.entity.createUpdate.data.branches.name"
                    hasDiv
                    title="Nombre"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    xl="3"
                    lg="6"/>
            </template>
            <template v-else-if="['sales'].includes(forms.entity.createUpdate.data.report?.code)">
                <InputSlot
                    hasDiv
                    title="Tipo"
                    :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                    isRequired
                    xl="4"
                    lg="4">
                    <template v-slot:input>
                        <v-select
                            v-model="forms.entity.createUpdate.data.sales.type"
                            :options="salesType"
                            :class="'bg-white'"
                            :clearable="false"/>
                    </template>
                </InputSlot>
                <template v-if="['by_month'].includes(forms.entity.createUpdate.data.sales.type?.code)">
                    <InputMonth
                        v-model="forms.entity.createUpdate.data.sales.start_month"
                        hasDiv
                        title="Mes de"
                        :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                        isRequired
                        xl="4"
                        lg="4"/>
                </template>
                <template v-else-if="['range_months'].includes(forms.entity.createUpdate.data.sales.type?.code)">
                    <InputMonth
                        v-model="forms.entity.createUpdate.data.sales.start_month"
                        hasDiv
                        title="Mes de"
                        :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                        isRequired
                        xl="4"
                        lg="4"/>
                    <InputMonth
                        v-model="forms.entity.createUpdate.data.sales.end_month"
                        hasDiv
                        title="Mes al"
                        :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                        isRequired
                        xl="4"
                        lg="4"/>
                </template>
                <template v-else-if="['by_date'].includes(forms.entity.createUpdate.data.sales.type?.code)">
                    <InputDate
                        v-model="forms.entity.createUpdate.data.sales.start_date"
                        hasDiv
                        title="Fecha del"
                        :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                        isRequired
                        xl="4"
                        lg="4"/>
                </template>
                <template v-else-if="['range_dates'].includes(forms.entity.createUpdate.data.sales.type?.code)">
                    <InputDate
                        v-model="forms.entity.createUpdate.data.sales.start_date"
                        hasDiv
                        title="Fecha del"
                        :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                        isRequired
                        xl="4"
                        lg="4"/>
                    <InputDate
                        v-model="forms.entity.createUpdate.data.sales.end_date"
                        hasDiv
                        title="Fecha al"
                        :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                        isRequired
                        xl="4"
                        lg="4"/>
                </template>
            </template>
        </template>
        <InputSlot
            v-if="isDefined({value: forms.entity.createUpdate.data.report?.code})"
            hasDiv
            :isInputGroup="false"
            xl="3"
            lg="3">
            <template v-slot:input>
                <button class="btn btn-primary waves-effect" type="button" @click="exportReport({})">
                    <i class="fa fa-print"></i>
                    <span class="ms-2">Generar reporte</span>
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
                            customers: {
                                document_number: "",
                                name: ""
                            },
                            users: {
                                document_number: "",
                                name: ""
                            },
                            items: {
                                name: "",
                                description: ""
                            },
                            branches: {
                                name: ""
                            },
                            sales: {
                                type: null,
                                start_date: "",
                                end_date: "",
                                start_month: "",
                                end_month: ""
                            }
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

            return new Promise(resolve => {

                this.forms.entity.createUpdate.data.report = (this.reports).length > 0 ? this.reports[0] : null;
                this.forms.entity.createUpdate.data.sales.type = (this.salesType).length > 0 ? this.salesType[0] : null;

                resolve(true);

            });

        },
        // Forms
        async exportReport({}) {

            const functionName = "exportReport";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let report = this.forms.entity.createUpdate.data.report;

            let form = JSON.parse(JSON.stringify(this.forms.entity.createUpdate.data[report?.code]));

            const validateForm = this.validateForm({functionName, form: {...form, report}});

            if(validateForm?.bool) {

                try {

                    if(["sales"].includes(report?.code)) {

                        let saleType = form?.type?.code;

                        delete form.type;

                        form.type = saleType;

                    }

                    const response = await axios.get(this.config.entity.routes.consult+`/${report?.code}`, {
                    responseType: 'blob', // Importante para manejar archivos
                    params: {...form}
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

                // this.formErrors({functionName, type: "set", errors: validateForm});
                Alerts.generateAlert({messages: validateForm?.msg})

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "exportReport":
                    this.forms.entity.createUpdate.data.report = null;
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["exportReport"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null}) {

            let result = {
                bool: true
            };

            if(["exportReport"].includes(functionName)) {

                result.msg = [];

                if(["sales"].includes(form.report.code)) {

                    if(!this.isDefined({value: form?.type})) {

                        result.msg.push(`Tipo: ${this.config.forms.errors.labels.required}`);
                        result.bool = false;

                    }

                    if(["by_month"].includes(form.type.code)) {

                        if(!this.isDefined({value: form?.start_month})) {

                            result.msg.push(`Mes de: ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                    }else if(["range_months"].includes(form.type.code)) {

                        if(!this.isDefined({value: form?.start_month})) {

                            result.msg.push(`Mes de: ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                        if(!this.isDefined({value: form?.end_month})) {

                            result.msg.push(`Mes al: ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                    }else if(["by_date"].includes(form.type.code)) {

                        if(!this.isDefined({value: form?.start_date})) {

                            result.msg.push(`Fecha del: ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                    }else if(["range_dates"].includes(form.type.code)) {

                        if(!this.isDefined({value: form?.start_date})) {

                            result.msg.push(`Fecha del: ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                        if(!this.isDefined({value: form?.end_date})) {

                            result.msg.push(`Fecha al: ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                    }

                }

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

        },
        salesType: function() {

            return [
                {code: "by_month", label: "Por mes"},
                {code: "range_months", label: "Entre meses"},
                {code: "by_date", label: "Por fecha"},
                {code: "range_dates", label: "Entre fechas"},
            ];

        }
    }
};
</script>
