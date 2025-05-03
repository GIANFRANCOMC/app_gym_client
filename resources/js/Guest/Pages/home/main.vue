<template>
    <div class="container flex-grow-1 container-p-y">
        <div class="text-end mt-4 mt-md-2 mb-3">
            <span class="badge bg-label-primary">Síguenos</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">Conecta con nosotros</span>
        </h4>
        <div class="text-center text-muted pb-2 mb-3">
            <span class="d-block fw-regular">Estamos activos en redes sociales. ¡Escríbenos o síguenos para estar al tanto de nuestras novedades!</span>
        </div>
        <div class="row g-6">
            <div class="col-lg-5">
                <div class="contact-img-box position-relative border p-2 h-100">
                    <img :src="'../'+forms.entity.home.data?.ownerApp?.assets?.img?.contact_us" alt="logo" class="contact-img w-100 scaleX-n1-rtl"/>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-2 justify-content-center align-items-center">
                            <div class="col-xl-auto col-lg-6 col-md-12 col-sm-12">
                                <a href="javascript:void(0)" class="btn btn-info waves-effect" @click="openUrl(forms.entity.home.data.facebook)">
                                    <span>Visítanos en Facebook</span>
                                    <i class="tf-icons fa-brands fa-facebook-f fs-5 ms-2"></i>
                                </a>
                            </div>
                            <div class="col-xl-auto col-lg-6 col-md-12 col-sm-12">
                                <a href="javascript:void(0)" class="btn btn-danger waves-effect" @click="openUrl(forms.entity.home.data.instagram)">
                                    <span>Visítanos en Instagram</span>
                                    <i class="tf-icons fa-brands fa-instagram fs-5 ms-2"></i>
                                </a>
                            </div>
                            <div class="col-xl-auto col-lg-6 col-md-12 col-sm-12">
                                <a href="javascript:void(0)" class="btn btn-success waves-effect" @click="openUrl(forms.entity.home.data.whatsapp)">
                                    <span>Visítanos en Whatsapp</span>
                                    <i class="tf-icons fa-brands fa-whatsapp fs-5 ms-2"></i>
                                </a>
                            </div>
                        </div>
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

let barChartInstance = null;

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
            // this.initData({});

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
                            document_number: "",
                            legal_name: "",
                            commercial_name: "",
                            tagline: "",
                            description: "",
                            address: "",
                            telephone: "",
                            email: "",
                            facebook: "",
                            instagram: "",
                            whatsapp: "",
                            ownerApp: null
                        }
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "home"})
                }
            }
        };
    },
    methods: {
        // Init
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, data: {page: "main"}, showAlert: true});

            this.options.companies = initParams.data?.config?.companies;
            this.options.company   = initParams.data?.config?.company;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                const company = (this.options.company?.records ?? []).length > 0 ? this.options.company?.records[0] : null;

                this.forms.entity.home.data.document_number = company?.document_number;
                this.forms.entity.home.data.legal_name      = company?.legal_name;
                this.forms.entity.home.data.commercial_name = company?.commercial_name;
                this.forms.entity.home.data.tagline         = company?.tagline;
                this.forms.entity.home.data.description     = company?.description;
                this.forms.entity.home.data.address         = company?.address;
                this.forms.entity.home.data.telephone       = company?.telephone;
                this.forms.entity.home.data.email           = company?.email;
                this.forms.entity.home.data.facebook        = company?.facebook;
                this.forms.entity.home.data.instagram       = company?.instagram;
                this.forms.entity.home.data.whatsapp        = company?.whatsapp;
                this.forms.entity.home.data.ownerApp        = company?.ownerApp;

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
        modalActionsEntity({record = null}) {

            const whatsapp = record?.holder?.phone_number ?? "";
            const email    = record?.holder?.email ?? "";

            this.forms.entity.home.extras.modals.actions.data = {...record, extras: {}, whatsapp, email};

            Alerts.modals({type: "show", id: this.forms.entity.home.extras.modals.actions.id});

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        fixedNumber(value) {

            return Utils.fixedNumber(value);

        },
        separatorNumber(value) {

            return Utils.separatorNumber(value);

        },
        legibleFormatDate({dateString = null, type = "datetime"}) {

            return Utils.legibleFormatDate({dateString, type});

        },
        openUrl(url = null) {

            window.open(url, "_blank")

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [this.config.entity.page];

        },
        lastSales: function() {

            return (this.forms.entity.home.data.sales?.all?.records ?? []).slice(0, 10);

        }
    }
};
</script>
