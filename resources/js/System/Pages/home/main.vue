<template>
    <!-- <Breadcrumb :list="breadcrumbTitles"/> -->

    <!-- Content -->
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6 col-sm-12" v-for="(section, indexSection) in config?.essential?.sections" :key="indexSection">
            <div class="card border border-light shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded-circle bg-info-1 d-flex align-items-center justify-content-center">
                                <i :class="section?.section?.dom_icon"></i>
                            </span>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark" v-text="section?.section?.dom_label"></h5>
                    </div>
                    <template v-for="(subSection, indexSubSection) in section?.subSections" :key="indexSubSection">
                        <a class="text-dark d-block py-1 px-2 rounded" v-text="subSection?.dom_label" href="javascript:void(0)"></a>
                    </template>
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

let barChartInstance = null;

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
            this.initData({});

        }

    },
    data() {
        return {
            forms: {
                entity: {
                    home: {
                        extras: {
                            modals: {
                                actions: {
                                    id: Utils.uuid(),
                                    data: {
                                        id: null,
                                        extras: {},
                                        whatsapp: "",
                                        email: ""
                                    },
                                    errors: {}
                                }
                            }
                        },
                        data: {
                            date: "",
                            sales: null,
                            branches: null,
                            users: null
                        }
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "home"}),
                    page: {
                        title: "Inicio",
                        active: true,
                        menu: {
                            id: "menu-item-home"
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

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.forms.entity.home.data.date = Utils.getCurrentDate();

                resolve(true);

            });

        },
        async initData({loading = false}) {

            if(loading) {

                Alerts.swals({type: "consult"});

            }

            if(!this.isDefined({value: this.forms.entity.home.data.date})) {

                this.forms.entity.home.data.date = Utils.getCurrentDate();

            }

            let initData = await Requests.get({route: this.config.entity.routes.initData, data: {page: "main", date: this.forms.entity.home.data.date}, showAlert: true});

            this.forms.entity.home.data.sales    = initData.data?.data?.sales;
            this.forms.entity.home.data.branches = initData.data?.data?.branches;
            this.forms.entity.home.data.users    = initData.data?.data?.users;

            if(loading) {

                Alerts.swals({show: false});

            }

            return Requests.valid({result: initData});

        },
        // Entity forms
        //
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [this.config.entity.page];

        }
    }
};
</script>
