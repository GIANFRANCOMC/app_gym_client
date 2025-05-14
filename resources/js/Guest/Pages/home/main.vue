<template>
    <div class="container-xxl flex-grow-1 container-p-y mt-5">
        <div class="landing-hero hero-text-box text-center position-relative rounded py-4">
            <div class="my-5">
                <h1 class="text-primary hero-title display-6 fw-extrabold" v-text="forms.entity.home.data.tagline"></h1>
                <h2 class="hero-sub-title h5 mb-6 px-4" v-text="forms.entity.home.data.description"></h2>
            </div>
        </div>
    </div>
    <div class="container flex-grow-1 container-p-y" v-if="isDefined({value: forms.entity.home.data.document_number})">
        <div class="row align-items-center bg-white px-5 px-md-4 rounded shadow-lg mx-0 py-xl-0 py-md-3 py-sm-3 py-3">
            <div class="col-lg-6">
                <h4 class="text-primary fw-bold mb-1">¡Es tu momento!</h4>
                <h6 class="mb-8">Empieza hoy tu transformación.<br/>No esperes más para alcanzar tu mejor versión.</h6>
                <a href="javascript:void(0)" class="btn btn-lg btn-success waves-effect" @click="openUrl(forms.entity.home.data.whatsapp)">
                    <i class="tf-icons fa-brands fa-whatsapp fs-5"></i>
                    <span class="ms-2">Contáctanos en Whatsapp</span>
                </a>
            </div>
            <div class="col-lg-6 pt-lg-12 text-center text-lg-end">
                <img :src="getAsset(forms.entity.home.data.logotype)" alt="Logo" class="img-fluid" width="40%">
            </div>
        </div>
    </div>
    <div class="container flex-grow-1 container-p-y" v-if="items.length > 0">
        <h4 class="text-center mb-1">
            <span class="position-relative fs-3 fw-extrabold z-1 text-dark">Nuestros productos destacados</span>
        </h4>
        <div class="text-center text-muted pb-2 mb-3">
            <span class="d-block fw-regular">Conoce nuestros productos y planes y encuentra el ideal para ti.</span>
        </div>
        <div class="row g-6">
            <div class="col-12">
                <div class="swiper" id="swiper-items">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="item in items" :key="item.id">
                            <div class="card">
                                <div class="card-body d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold fs-5 text-dark" v-text="item?.name"></span>
                                        </div>
                                        <div class="badge mt-1" :class="{'bg-label-success': item.type === 'product', 'bg-label-primary': item.type === 'service', 'bg-label-info': item.type === 'subscription'}">
                                            <i :class="{'fa fa-box': item.type === 'product', 'fa fa-gear': item.type === 'service', 'fa-solid fa-dumbbell': item.type === 'subscription'}"></i>
                                            <span v-text="item?.formatted_type" class="ms-2"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination swiper-pagination-custom"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container flex-grow-1 container-p-y" v-if="isDefined({value: forms.entity.home.data.document_number})">
        <h4 class="text-center mb-1">
            <span class="position-relative fs-3 fw-extrabold z-1 text-dark">Conecta con nosotros</span>
        </h4>
        <div class="text-center text-muted pb-2 mb-3">
            <span class="d-block fw-regular">Estamos activos en redes sociales. ¡Escríbenos o síguenos para estar al tanto de nuestras novedades!</span>
        </div>
        <div class="row g-6">
            <div class="col-lg-4">
                <div class="contact-img-box position-relative p-2 h-100 pt-0">
                    <img :src="'../'+forms.entity.home.data?.ownerApp?.assets?.img?.contact_us" alt="logo" class="contact-img w-100 scaleX-n1-rtl rounded" width="40%"/>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body px-5">
                        <div class="row justify-content-start align-items-center my-3">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p class="fs-6 text-dark">
                                    <i class="fa fa-location-dot"></i>
                                    <span class="colon-at-end fw-regular ms-2">Dirección</span>
                                    <span class="ms-2" v-text="forms.entity.home.data.address"></span>
                                </p>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p class="fs-6 text-dark">
                                    <i class="fa fa-phone"></i>
                                    <span class="colon-at-end fw-regular ms-2">Teléfono</span>
                                    <span class="ms-2" v-text="forms.entity.home.data.telephone"></span>
                                </p>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p class="fs-6 text-dark">
                                    <i class="fa fa-envelope"></i>
                                    <span class="colon-at-end fw-regular ms-2">Correo electrónico</span>
                                    <span class="ms-2" v-text="forms.entity.home.data.email"></span>
                                </p>
                            </div>
                        </div>
                        <div class="row g-md-4 g-2 justify-content-center align-items-center mb-3">
                            <div class="col-xl-auto col-lg-6 col-md-12 col-sm-12 text-center">
                                <a href="javascript:void(0)" class="btn btn-info-1 waves-effect" @click="openUrl(forms.entity.home.data.facebook)">
                                    <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
                                    <span class="ms-2">Visítanos en Facebook</span>
                                </a>
                            </div>
                            <div class="col-xl-auto col-lg-6 col-md-12 col-sm-12 text-center">
                                <a href="javascript:void(0)" class="btn btn-danger waves-effect" @click="openUrl(forms.entity.home.data.instagram)">
                                    <i class="tf-icons fa-brands fa-instagram fs-5"></i>
                                    <span class="ms-2">Visítanos en Instagram</span>
                                </a>
                            </div>
                            <div class="col-xl-auto col-lg-12 col-md-12 col-sm-12 text-center">
                                <a href="javascript:void(0)" class="btn btn-success waves-effect" @click="openUrl(forms.entity.home.data.whatsapp)">
                                    <i class="tf-icons fa-brands fa-whatsapp fs-5"></i>
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
                    spaceBetween: 10,
                    speed: 400,
                    autoplay: {
                        delay: 2000,
                        disableOnInteraction: false
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 1,
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
