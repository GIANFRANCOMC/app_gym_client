<template>
    <Breadcrumb :list="breadcrumbTitles" />

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
    <template v-if="isDefined({value: forms.entity.createUpdate.data.customer?.code})">
        <div class="row g-3 mt-2">
            <div class="col-12">
                <div class="card mb-6">
                    <div class="user-profile-header-banner">
                        <!-- <img src="../../assets/img/pages/profile-banner.png" alt="Banner image" class="rounded-top"> -->
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            <!-- <img src="../../assets/img/avatars/1.png" alt="user image" class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img"> -->
                        </div>
                        <div class="flex-grow-1 mt-3 mt-lg-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4 class="mb-2 mt-lg-6" v-text="forms.entity.createUpdate.history.customers[forms.entity.createUpdate.data.customer?.code]?.customer?.name ?? ''"></h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="icon-base ti tabler-palette icon-lg"></i>
                                            <span class="fw-medium" v-text="forms.entity.createUpdate.history.customers[forms.entity.createUpdate.data.customer?.code]?.customer?.identity_document_type?.name ?? ''"></span>
                                            <span class="fw-medium" v-text="forms.entity.createUpdate.history.customers[forms.entity.createUpdate.data.customer?.code]?.customer?.document_number ?? ''"></span>
                                        </li>
                                    </ul>
                                </div>
                                <a href="javascript:void(0)" class="btn btn-primary mb-1 waves-effect waves-light" v-if="false">
                                    <i class="icon-base ti tabler-user-check icon-xs me-2"></i>Connected
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-2">
                        <li class="nav-item">
                            <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                                <i class="fa-solid fa-cash-register"></i>
                                <span class="ms-2">Ventas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                                <i class="fa-solid fa-binoculars"></i>
                                <span class="ms-2">Membresías</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                                <i class="fa-solid fa-binoculars"></i>
                                <span class="ms-2">Asistencias</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-12">
                <Sales :data="forms.entity.createUpdate.history?.customers[forms.entity.createUpdate.data.customer?.code]?.sales ?? []"/>
            </div>
            <div class="col-12">
                <Subscriptions :data="forms.entity.createUpdate.history?.customers[forms.entity.createUpdate.data.customer?.code]?.subscriptions ?? []"/>
            </div>
            <div class="col-12">
                <Attendances :data="forms.entity.createUpdate.history?.customers[forms.entity.createUpdate.data.customer?.code]?.attendances ?? []"/>
            </div>
        </div>
    </template>
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

            // let form = this.forms.entity.createUpdate.extras.modals.subscriptions;

            //form.data.loading = true;

            let form = this.forms.entity.createUpdate.data.customer;

            const getSubscriptions = await Utils.getTrackingCustomers({customer: {id: form.code}});

            this.forms.entity.createUpdate.history.customers[this.forms.entity.createUpdate.data.customer?.code] = getSubscriptions?.data?.tracking;

            //this.options.customers.subscriptions[this.forms.entity.createUpdate.data.holder?.data?.id] = Requests.valid({result: getSubscriptions}) ? getSubscriptions?.data?.subscriptions : false;

            //form.data.loading = false;


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
