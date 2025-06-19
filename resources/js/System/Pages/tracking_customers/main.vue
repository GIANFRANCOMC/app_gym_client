<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="row align-items-end g-3 mb-3 mb-md-4 py-2">
        <InputSlot
            hasDiv
            :isInputGroup="false"
            :divInputClass="['d-flex flex-wrap justify-content-center align-items-end gap-2 gap-md-3']"
            xl="4"
            lg="6">
            <template v-slot:input>
                <button type="button" class="btn btn-primary waves-effect" @click="modalCreateUpdateEntity({})">
                    <i class="fa fa-arrow-pointer"></i>
                    <span class="ms-2">Seleccionar cliente</span>
                </button>
            </template>
        </InputSlot>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            :divInputClass="['d-flex flex-wrap justify-content-start align-items-end gap-2 gap-md-3']"
            xl="8"
            lg="6">
            <template v-slot:input>
                <div v-if="isDefined({value: customerCurrent?.customer})">
                    <div>
                        <i class="fa fa-user"></i>
                        <span class="h4 fw-bold ms-2" v-text="customerCurrent?.customer?.name ?? ''"></span>
                    </div>
                    <div>
                        <span v-text="customerCurrent?.customer?.identity_document_type?.name ?? ''"></span>
                        <span class="fw-bold ms-2" v-text="customerCurrent?.customer?.document_number ?? ''"></span>
                    </div>
                </div>
                <div v-else class="alert alert-warning mb-0 w-100 text-center">
                    <i class="fa fa-warning"></i>
                    <span class="ms-2">Seleccione un cliente</span>
                </div>
            </template>
        </InputSlot>
    </div>
    <div class="row g-3 py-4 border-top">
        <template v-if="isDefined({value: customerCurrent?.customer})">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ps-1 px-md-5">
                <Timeline :data="customerCurrent ?? {}"/>
            </div>
        </template>
        <div v-else class="text-center">
            <WithoutData type="image"/>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" :id="forms.entity.createUpdate.extras.modals.default.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.createUpdate.extras.modals.default.titles.default"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <InputSlot
                            hasDiv
                            title="Cliente"
                            isRequired
                            xl="12"
                            lg="12">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.customer"
                                    :options="customers"
                                    :class="config.forms.classes.select2"
                                    :clearable="false"
                                    placeholder="Seleccione un cliente ..."/>
                            </template>
                        </InputSlot>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
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

        Utils.navbarItem("menu-item-trackings", {addClass: "open"});
        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            // this.listEntity({});

        }

    },
    data() {
        return {
            forms: {
                entity: {
                    createUpdate: {
                        extras: {
                            modals: {
                                default: {
                                    id: Utils.uuid(),
                                    titles: {
                                        default: "Seleccionar"
                                    }
                                }
                            }
                        },
                        data: {
                            customer: null
                        },
                        history: {
                            customerCurrent: null,
                            customers: {}
                        },
                        errors: {}
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "tracking_customers"}),
                    page: {
                        title: "Expediente",
                        active: true,
                        menu: {
                            id: "menu-item-trackings-customers"
                        }
                    }
                }
            }
        };
    },
    methods: {
        // Init
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, data: {page: "main"}, showAlert: true});

            this.options.customers = initParams.data?.config?.customers;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.modalCreateUpdateEntity({});

                resolve(true);

            });

        },
        // Forms
        modalCreateUpdateEntity({record = null}) {

            const functionName = "modalCreateUpdateEntity";

            // Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                //

            }else {

                //

            }

            // Alerts.swals({show: false});
            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.default.id});

        },
        async getTrackingCustomers() {

            let form = this.forms.entity.createUpdate.data.customer;

            if(this.isDefined({value: form?.code})) {

                Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.default.id});
                Alerts.swals({});

                const getSubscriptions = await Utils.getTrackingCustomers({customer: {id: form.code}});

                if(Requests.valid({result: getSubscriptions})) {

                    this.forms.entity.createUpdate.history.customers[this.forms.entity.createUpdate.data.customer?.code] = getSubscriptions?.data?.tracking;
                    this.forms.entity.createUpdate.history.customerCurrent = this.forms.entity.createUpdate.data.customer;

                }else {

                    this.forms.entity.createUpdate.history.customers[this.forms.entity.createUpdate.data.customer?.code] = {};

                }

                Alerts.swals({show: false});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.customer = null;
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["modalCreateUpdateEntity", "createUpdateEntity"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null, extras = null}) {

            let result = {
                bool: true
            };

            if(["createUpdateEntity"].includes(functionName)) {

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
        breadcrumbTitles: function() {

            return [{title: "Seguimiento"}, this.config.entity.page];

        },
        customers: function() {

            return this.options?.customers?.records.map(e => ({code: e.id, label: `${e.document_number} - ${e.name}`, data: e}));

        },
        customerCurrent: function() {

            return this.forms.entity.createUpdate.history?.customers[this.forms.entity.createUpdate.history.customerCurrent?.code];

        }
    },
    watch: {
        "forms.entity.createUpdate.data.customer": function(newValue, oldValue) {

            this.getTrackingCustomers();

        }
    }
};
</script>
