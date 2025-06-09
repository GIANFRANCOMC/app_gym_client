<template>
    <Breadcrumb :list="breadcrumbTitles" />

    <div>
        <div class="row">
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
                                    <h4 class="mb-2 mt-lg-6">John Doe</h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="icon-base ti tabler-palette icon-lg"></i>
                                            <span class="fw-medium">UX Designer</span></li>
                                    </ul>
                                </div>
                                <a href="javascript:void(0)" class="btn btn-primary mb-1 waves-effect waves-light">
                                    <i class="icon-base ti tabler-user-check icon-xs me-2"></i>Connected </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-sm-0 gap-2">
                        <li class="nav-item">
                            <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                                <i class="icon-base ti tabler-user-check icon-sm me-1_5"></i> Profile</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5">
                <div class="card mb-6">
                </div>
                <div class="card mb-6">
                </div>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card card-action mb-6">
                </div>
                <div class="row">
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
                            status: null
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
                        title: "Ficha técnica",
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

            //

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

        }
    },
    watch: {
        //
    }
};
</script>
