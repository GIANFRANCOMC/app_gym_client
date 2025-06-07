<template>
    <div :class="['container flex-grow-1 container-p-y', config.essential?.withMenu ? 'mt-5' : '']">
        <div class="text-end mb-3 mb-md-1">
            <div class="badge bg-info-1 rounded-pill ">
                <i class="fa-solid fa-location-dot"></i>
                <span class="ms-2" v-text="config.essential?.branch?.name+(isDefined({value: config.essential?.branch?.address}) ? ' / '+config.essential?.branch?.address : '')"></span>
            </div>
        </div>
        <div class="text-center mb-2 mb-md-1">
            <img :src="'/storage/'+config.essential?.company?.logotype" alt="Logo" style="max-height: 80px;" class="border border-light shadow-sm">
        </div>
        <div class="text-center mb-2 mb-md-2">
            <span class="position-relative fw-extrabold z-1 h5">Escanea tu carnet para registrar tu ingreso o salida</span>
        </div>

        <!-- Content -->
        <div class="row justify-content-center g-3">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="card shadow-sm p-3">
                    <div class="row justify-content-center g-3">
                        <InputSlot
                            :hasDiv="true"
                            :isInputGroup="false"
                            xl="10"
                            lg="10">
                            <template v-slot:input>
                                <div class="w-100">
                                    <CodeScanner
                                        ref="scannerQr"
                                        :showControls="true"
                                        :qrbox="290"
                                        :fps="23"
                                        :limitScan="-1"
                                        :canProcess="!forms.entity.qrcode.config.isProcessing"
                                        @result="onScanCustomer"/>
                                </div>
                            </template>
                        </InputSlot>
                    </div>
                </div>
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
                            branch: null,
                            customers: []
                        },
                        config: {
                            isProcessing: false
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

                this.lists.entity.filters.branch     = {code: this.config.essential?.branch?.id, label: this.config.essential?.branch?.name};
                this.forms.entity.qrcode.data.branch = this.config.essential?.branch;

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
        async onScanCustomer(decodedText, decodedResult) {

            const functionName = "onScanCustomer";

            if(this.forms.entity.qrcode.config.isProcessing) return;

            try {

                Utils.playSound("attendances/scan.mp3");

                console.log(decodedText, decodedResult);
                let dataScan = JSON.parse(decodedResult?.result?.text);

                const dataScanBp = Utils.decodeBase64UTF8(dataScan?.bp);
                const bp = JSON.parse(dataScanBp);

                if(!this.isDefined({value: dataScanBp}) || !this.isDefined({value: bp})) {

                    this.$refs.scannerQr.decrementScanCounter();
                    Alerts.generateAlert({type: "warning", msgContent: `<span class="d-block fw-semibold">No pudimos validar el QR. Intenta escanearlo nuevamente o verifica que sea el correcto.</span>`});

                }else {

                    let el = this;

                    const id = parseInt(bp?.id);

                    if(id > 0) {

                        Alerts.swals({});

                        // Set data
                        this.forms.entity.qrcode.config.isProcessing = true;

                        this.forms.entity.qrcode.data.customers = [{customer_id: id}];

                        // Validate form
                        let form = Utils.cloneJson(this.forms.entity.qrcode.data);

                        form.branch_id = form?.branch?.id;

                        delete form.branch;

                        const qrcode = await Requests.post({route: this.config.entity.routes.qrCamera, data: form});

                        const isValid = Requests.valid({result: qrcode});

                        if(isValid) {

                            // Alerts.toastrs({type: "success", subtitle: qrcode?.data?.msg});
                            // Alerts.swals({show: false});

                            this.clearForm({functionName});

                        }else {

                            // this.formErrors({functionName, type: "set", errors: qrcode?.errors ?? []});
                            // Alerts.toastrs({type: "error", subtitle: qrcode?.data?.msg});
                            // Alerts.swals({show: false});

                        }

                        // Show response
                        if((qrcode?.data?.attendances ?? []).length === 0) {

                            Alerts.generateAlert({type: isValid ? "success" : "error", msgContent: qrcode?.data?.msg});

                        }else if((qrcode?.data?.attendances ?? []).length === 1) {

                            Alerts.generateAlert({type: qrcode?.data?.attendances[0]?.bool ? "success" : "error", msgContent: qrcode?.data?.attendances[0]?.msg});

                        }else {

                            Alerts.generateAlert({type: isValid ? "success" : "error", messages: qrcode?.data?.attendances.map(e => `${e.bool ? "<i class='fa fa-check-circle text-success'></i>" : "<i class='fa fa-times-circle text-danger'></i>"} <span class='ms-1'>${e.msg}</span>`), msgContent: `<div class="fw-semibold mb-2">${qrcode?.data?.msg}</div>`});

                        }

                        setTimeout(() => {

                            Alerts.swals({show: false});
                            el.forms.entity.qrcode.config.isProcessing = false;

                        }, 5000);

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
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "onScanCustomer":
                    this.forms.entity.qrcode.data.customers = [];
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["onScanCustomer"].includes(functionName)) {

                this.forms.entity.qrcode.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null, extras = null}) {

            let result = {
                bool: true
            };

            if(["onScanCustomer"].includes(functionName)) {

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
    }
};
</script>
