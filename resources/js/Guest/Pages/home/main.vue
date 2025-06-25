<template>
    <br/>
    <div class="d-flex container-p-y mt-5 mb-5">
        <div class="text-center w-100 mt-4">
            <div class="mt-3">
                <span class="text-primary-custom fs-1 fw-bold d-block" v-text="forms.entity.home.data.tagline"></span>
                <span class="text-white-custom fs-5 d-block" v-text="forms.entity.home.data.description"></span>
            </div>
        </div>
    </div>
    <div class="container flex-grow-1 container-p-y my-0 my-md-2" v-if="isDefined({value: forms.entity.home.data.document_number})">
        <div class="row g-3 align-items-center bg-down-color-primary px-4 py-4 mx-0">
            <div class="col-lg-9">
                <span class="text-primary-custom fw-bold text-uppercase fs-4 d-block mb-2">🏋️ ¡Es tu momento!</span>
                <span class="text-white-custom d-block mb-3">Empieza hoy tu transformación.<br/>No esperes más para alcanzar tu mejor versión.</span>
                <a href="javascript:void(0)" class="btn btn-success waves-effect animation-pulse" @click="openUrl(forms.entity.home.data.whatsapp)">
                    <i class="tf-icons fa-brands fa-whatsapp"></i>
                    <span class="ms-2">Contáctanos en Whatsapp</span>
                </a>
            </div>
            <div class="col-lg-3 text-center">
                <img :src="getAsset(forms.entity.home.data.logotype)" alt="Logo" class="img-fluid w-60">
            </div>
        </div>
    </div>
    <div class="container flex-grow-1 container-p-y my-0 my-md-2" v-if="items.length > 0">
        <div class="text-center mb-1">
            <span class="text-primary-custom fw-bold text-uppercase fs-4 d-block">Nuestros productos destacados</span>
            <span class="text-white-custom d-block">Conoce nuestros productos, planes y encuentra el ideal para ti.</span>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="swiper" id="swiper-items">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="item in items" :key="item.id">
                            <div class="card bg-down-color-primary cursor-pointer" @click="openUrl(forms.entity.home.data.whatsapp)">
                                <div class="card-body pt-2 pb-0">
                                    <div class="d-flex justify-content-between flex-wrap">
                                        <template v-if="isDefined({value: item?.currency?.sign}) && isDefined({value: item?.price})">
                                            <div class="text-nowrap">
                                                <span>🤩</span>
                                                <small class="text-primary-custom fw-semibold ms-2">Lo quiero</small>
                                            </div>
                                            <span class="text-white-custom text-nowrap" v-text="item?.currency?.sign+' '+item?.price"></span>
                                        </template>
                                        <div v-else class="text-nowrap">
                                            <span>🤩</span>
                                            <small class="text-primary-custom fw-semibold ms-2">Lo quiero</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-3">
                                    <span class="fw-bold fs-5 text-white-custom text-start d-block" v-text="truncate({value: item?.name, length: 13})"></span>
                                </div>
                                <div class="card-footer py-1 bg-primary-custom text-white-custom text-center fw-semibold">
                                    <span v-text="item.type === 'product' ? '📦' : item.type === 'service' ? '🛠️' : item.type === 'subscription' ? '🎫' : ''"></span>
                                    <span v-text="item?.formatted_type" class="ms-2"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination swiper-pagination-custom"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container flex-grow-1 container-p-y my-0 mt-md-2" v-if="isDefined({value: forms.entity.home.data.document_number})">
        <div class="text-center mb-1">
            <span class="text-primary-custom fw-bold text-uppercase fs-4 d-block">Conecta con nosotros</span>
            <span class="text-white-custom d-block">Estamos activos en redes sociales. ¡Escríbenos o síguenos para estar al tanto de nuestras novedades!</span>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card bg-down-color-primary">
                    <div class="card-body px-5">
                        <div class="row justify-content-start align-items-center my-3">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p>
                                    <span>📍</span>
                                    <span class="colon-at-end text-primary-custom ms-2 text-uppercase fw-semibold">Dirección</span>
                                    <span class="ms-2 text-white-custom" v-text="forms.entity.home.data.address"></span>
                                </p>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                                <p>
                                    <span>📞</span>
                                    <span class="colon-at-end text-primary-custom ms-2 text-uppercase fw-semibold">Teléfono</span>
                                    <span class="ms-2 text-white-custom" v-text="forms.entity.home.data.telephone"></span>
                                </p>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                                <p>
                                    <span>📧</span>
                                    <span class="colon-at-end text-primary-custom ms-2 text-uppercase fw-semibold">Correo electrónico</span>
                                    <span class="ms-2 text-white-custom" v-text="forms.entity.home.data.email"></span>
                                </p>
                            </div>
                        </div>
                        <div class="row g-md-4 g-2 justify-content-center align-items-center mb-3">
                            <div class="col-xl-auto col-lg-6 col-md-12 col-sm-12 text-center">
                                <a href="javascript:void(0)" class="btn btn-info-1 waves-effect w-100" @click="openUrl(forms.entity.home.data.facebook)">
                                    <i class="tf-icons fa-brands fa-facebook-square"></i>
                                    <span class="ms-2">Visítanos en Facebook</span>
                                </a>
                            </div>
                            <div class="col-xl-auto col-lg-6 col-md-12 col-sm-12 text-center">
                                <a href="javascript:void(0)" class="btn btn-danger waves-effect w-100" @click="openUrl(forms.entity.home.data.instagram)">
                                    <i class="tf-icons fa-brands fa-instagram"></i>
                                    <span class="ms-2">Visítanos en Instagram</span>
                                </a>
                            </div>
                            <div class="col-xl-auto col-lg-12 col-md-12 col-sm-12 text-center">
                                <a href="javascript:void(0)" class="btn btn-success waves-effect w-100" @click="openUrl(forms.entity.home.data.whatsapp)">
                                    <i class="tf-icons fa-brands fa-whatsapp"></i>
                                    <span class="ms-2">Contáctanos en Whatsapp</span>
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

export default {
    components: {
        //
    },
    mounted: async function() {

        // Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            // Alerts.swals({show: false});

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
                            logotype: "",
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
            this.options.items     = initParams.data?.config?.items;

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
                this.forms.entity.home.data.logotype        = company?.logotype;
                this.forms.entity.home.data.facebook        = company?.facebook;
                this.forms.entity.home.data.instagram       = company?.instagram;
                this.forms.entity.home.data.whatsapp        = company?.whatsapp;
                this.forms.entity.home.data.ownerApp        = company?.ownerApp;

                const swiper = new Swiper('#swiper-items', {
                    direction: "horizontal",
                    loop: true,
                    slidesPerView: 4,
                    spaceBetween: 14,
                    speed: 400,
                    autoplay: {
                        delay: 2000,
                        disableOnInteraction: false
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 2,
                        },
                        576: {
                            slidesPerView: 2,
                        },
                        768: {
                            slidesPerView: 3,
                        },
                        992: {
                            slidesPerView: 4,
                        },
                    }
                });

                resolve(true);

            });

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
        truncate({value, length}) {

            return Utils.truncate({value, length});

        },
        openUrl(url = null) {

            window.open(url, "_blank")

        },
        getAsset(path) {

            const baseUrl = "/storage/";

            return `${baseUrl}${path}`;

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [this.config.entity.page];

        },
        items: function() {

            return (this.options?.items?.records ?? []).slice(0, 8);

        }
    }
};
</script>
