<template>
    <Breadcrumb :list="breadcrumbTitles" />

    <!-- Content -->
     <div class="row align-items-end g-3 mb-3 mb-md-4">
        <InputSlot
            hasDiv
            title="Cliente"
            :titleClass="[config.forms.classes.title]"
            xl="6"
            lg="6">
            <template v-slot:input>
                <v-select
                    v-model="forms.entity.createUpdate.data.customer"
                    :options="customers"
                    :class="config.forms.classes.select2"
                    :clearable="true"
                    placeholder="Seleccione un cliente ..."/>
            </template>
        </InputSlot>
    </div>
    <div v-if="isDefined({value: forms.entity.createUpdate.data.customer?.code})">
        <div class="row g-3">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex align-items-center justify-content-end mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="h4 d-block mb-0 text-end fw-bold" v-text="forms.entity.createUpdate.history.customers[forms.entity.createUpdate.data.customer?.code]?.customer?.name ?? ''"></span>
                            <div class="d-flex justify-content-end gap-2">
                                <span v-text="forms.entity.createUpdate.history.customers[forms.entity.createUpdate.data.customer?.code]?.customer?.identity_document_type?.name ?? ''"></span>
                                <span class="fw-bold" v-text="forms.entity.createUpdate.history.customers[forms.entity.createUpdate.data.customer?.code]?.customer?.document_number ?? ''"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <Timeline :data="forms.entity.createUpdate.history?.customers[forms.entity.createUpdate.data.customer?.code] ?? {}"/>
            </div>
            <div class="col-12" v-show="false">
                <Sales :data="forms.entity.createUpdate.history?.customers[forms.entity.createUpdate.data.customer?.code]?.sales ?? []"/>
            </div>
            <div class="col-12" v-show="false">
                <Subscriptions :data="forms.entity.createUpdate.history?.customers[forms.entity.createUpdate.data.customer?.code]?.subscriptions ?? []"/>
            </div>
            <div class="col-12" v-show="false">
                <Attendances :data="forms.entity.createUpdate.history?.customers[forms.entity.createUpdate.data.customer?.code]?.attendances ?? []"/>
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
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "tracking_customers", type: "list"})
                    },
                    filters: {
                        branch: null,
                        customer: null,
                        start_date: "",
                        end_date: "",
                        status: ""
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                entity: {
                    createUpdate: {
                        extras: {
                            modals: {
                                actions: {
                                    id: Utils.uuid(),
                                    data: {
                                        id: null
                                    }
                                }
                            }
                        },
                        data: {
                            id: null,
                            customer: null,
                            status: null
                        },
                        history: {
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

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {branch_id: filters?.branch?.code, customer_id: filters?.customer?.code, start_date: filters?.start_date, end_date: filters?.end_date, status: filters?.status};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: filterJson}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        modalActionsEntity({record = null}) {

            this.forms.entity.createUpdate.extras.modals.actions.data = record;

            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.actions.id});

        },
        async getTrackingCustomers() {

            Alerts.swals({});

            let form = this.forms.entity.createUpdate.data.customer;

            if(this.isDefined({value: form.code})) {

                const getSubscriptions = await Utils.getTrackingCustomers({customer: {id: form.code}});

                this.forms.entity.createUpdate.history.customers[this.forms.entity.createUpdate.data.customer?.code] = Requests.valid({result: getSubscriptions}) ? getSubscriptions?.data?.tracking : {};

            }

            Alerts.swals({show: false});

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    //
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

        }
    },
    watch: {
        "forms.entity.createUpdate.data.customer": function(newValue, oldValue) {

            this.getTrackingCustomers();

        }
    }
};
</script>
