<template>
    <div :class="['container flex-grow-1 container-p-y', withMenu ? 'mt-5' : '']">
        <div class="text-end mb-3 mb-md-1">
            <span class="badge bg-label-success">Control de Asistencia</span>
        </div>
        <div class="text-center mb-2 mb-md-1">
            <img :src="'/storage/'+company.logotype" alt="Logo" style="max-height: 80px;" class="border border-light shadow-sm">
        </div>
        <h4 class="text-center mb-2 mb-md-2">
            <span class="position-relative fw-extrabold z-1">Escanea tu carnet para registrar tu ingreso</span>
        </h4>

        <!-- Content -->
        <div class="card shadow-sm p-3">
            <div class="row g-3">
                <InputSlot
                    :hasDiv="false"
                    :isInputGroup="false"
                    :divInputClass="['d-flex justify-content-center']"
                    xl="12"
                    lg="12">
                    <template v-slot:input>
                        <div class="w-100">
                            <div class="text-center text-muted">
                                <span class="fw-regular text-dark colon-at-end">Sucursal</span>
                                <span class="fw-bold text-dark ms-1" v-text="branch?.name"></span>
                            </div>
                        </div>
                    </template>
                </InputSlot>
                <InputSlot
                    :hasDiv="false"
                    :isInputGroup="false"
                    :divInputClass="['d-flex justify-content-center']"
                    xl="12"
                    lg="12">
                    <template v-slot:input>
                        <div class="w-100">
                            <CodeScanner
                                ref="scannerQr"
                                :qrbox="300"
                                :limitScan="1"
                                @result="onScanCustomer"/>
                        </div>
                    </template>
                </InputSlot>
            </div>
        </div>
    </div>
</template>

<script>
import * as Alerts    from "../../Helpers/Alerts.js";
import * as Constants from "../../Helpers/Constants.js";
import * as Requests  from "../../Helpers/Requests.js";
import * as Utils     from "../../Helpers/Utils.js";

export default {
    components: {
        //
    },
    mounted: async function() {

        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            // this.listEntity({});

            if(this.$refs?.scannerQr?.startScanner) {

                this.$refs.scannerQr.startScanner();

            }

        }

    },
    data() {
        return {
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "tracking_attendances", type: "list"})
                    },
                    filters: {
                        branch: null
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                entity: {
                    qrcode: {
                        data: {
                            id: null,
                            branch: null,
                            customer: null
                        },
                        errors: {}
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "tracking_attendances"})
                }
            }
        };
    },
    methods: {
        // Init
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, data: {page: "main"}, showAlert: true});

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.lists.entity.filters.branch = {code: this.branch?.id, label: this.branch?.name};

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {branch_id: filters?.branch?.code};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: filterJson}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Qrcode
        onScanCustomer(decodedText, decodedResult) {

            try {

                console.log(decodedText, decodedResult);
                let dataScan = JSON.parse(decodedResult?.result?.text);

                const dataScanBp = Utils.decodeBase64UTF8(dataScan?.bp);
                const bp = JSON.parse(dataScanBp);

                if(!this.isDefined({value: dataScanBp}) || !this.isDefined({value: bp})) {

                    this.$refs.scannerQr.decrementScanCounter();
                    Alerts.generateAlert({type: "warning", msgContent: `<span class="d-block fw-semibold">No pudimos validar el QR. Intenta escanearlo nuevamente o verifica que sea el correcto.</span>`});

                }else {

                    const id = parseInt(bp?.id);

                    if(id > 0) {

                        this.forms.entity.qrcode.data.customer = {id: id};
                        Alerts.generateAlert({type: "success", msgContent: `<span class="d-block">Cliente escaneado.</span><span class="d-block fw-semibold mt-1">Se ha agregado a los clientes escaneados.</span>`});

                        // Logic - qr

                    }else {

                        this.$refs.scannerQr.decrementScanCounter();
                        Alerts.generateAlert({type: "warning", msgContent: `<span class="d-block fw-semibold">Intenta escanearlo nuevamente o verifica que sea el correcto.</span>`});

                    }

                }

            }catch(e) {

                console.log(e);
                this.$refs.scannerQr.decrementScanCounter();
                Alerts.generateAlert({type: "warning", msgContent: `<span class="d-block fw-semibold">No pudimos validar el QR. Intenta escanearlo nuevamente o verifica que sea el correcto.</span>`});

            }

        },
        async qrcodeEntity() {

            const functionName = "qrcodeEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.qrcode.data);

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive", modal: this.forms.entity.qrcode.extras.modals.default}});

            if(validateForm?.bool) {

                form.branch_id = form?.branch?.code;

                delete form.branch;

                form.customers.forEach(customer => {

                    customer.customer_id = customer.code;

                    delete customer.code;
                    delete customer.label;
                    delete customer.data;

                });

                const qrcode = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.qrcodeUpdate, data: form, id: form.id}) :
                                                                         Requests.post({route: this.config.entity.routes.qrcodeStore, data: form}));

                if(Requests.valid({result: qrcode})) {

                    // Alerts.toastrs({type: "success", subtitle: qrcode?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "success", messages: qrcode?.data?.attendances.map(e => `${e.bool ? "<i class='fa fa-check-circle text-success'></i>" : "<i class='fa fa-times-circle text-danger'></i>"} <span class='ms-1'>${e.msg}</span>`), msgContent: `<div class="fw-semibold mb-2">${qrcode?.data?.msg}</div>`});

                    this.clearForm({functionName});
                    this.forms.entity.qrcode.data.customers = [];

                    this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: qrcode?.errors ?? []});
                    // Alerts.toastrs({type: "error", subtitle: qrcode?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "error", messages: qrcode?.data?.attendances.map(e => `${e.bool ? "<i class='fa fa-check-circle text-success'></i>" : "<i class='fa fa-times-circle text-danger'></i>"} <span class='ms-1'>${e.msg}</span>`), msgContent: `<div class="fw-semibold mb-2">${qrcode?.data?.msg}</div>`});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                Alerts.generateAlert({messages: Utils.getErrors({errors: validateForm}), msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "qrcodeEntity":
                    //
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["qrcodeEntity"].includes(functionName)) {

                //

            }

        },
        validateForm({functionName, form = null, extras = null}) {

            let result = {
                bool: true
            };

            if(["qrcodeEntity"].includes(functionName)) {

                //

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        legibleFormatDate({dateString = null, type = "datetime"}) {

            return Utils.legibleFormatDate({dateString, type});

        }
    },
    computed: {
        withMenu: function() {

            return window?.withMenu ?? true;

        },
        company: function() {

            return window?.company ?? null;

        },
        branch: function() {

            return window?.branch ?? null;

        }
    }
};
</script>
