<template>
    <!-- <Breadcrumb :list="breadcrumbTitles"/> -->

    <!-- Content -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-end align-items-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" v-model="forms.entity.createUpdate.config.showOnlyFavorites" id="toggleFavs">
                    <label for="toggleFavs">Mostrar solo favoritos</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-xl-4 col-md-6 col-sm-12" v-for="(section, indexSection) in sections" :key="indexSection">
            <div class="card border border-light shadow-sm h-100">
                <div class="card-body">
                    <template v-if="(section?.sub_sections ?? []).length === 1">
                        <button type="button"
                                :class="['position-absolute top-0 start-100 translate-middle badge badge-center rounded-pill border-light', (section.sub_sections[0]?.hovered ? !section.sub_sections[0]?.is_favorite : section.sub_sections[0]?.is_favorite) ? 'bg-warning' : 'bg-secondary']"
                                data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-original-title="(section.sub_sections[0]?.hovered ? !section.sub_sections[0]?.is_favorite : section.sub_sections[0]?.is_favorite) ? 'Quitar de favoritos' : 'Marcar como favorito'"
                                @click="changeFavorite(section.sub_sections[0])"
                                @mouseenter="section.sub_sections[0].hovered = true"
                                @mouseleave="section.sub_sections[0].hovered = false">
                            <i :class="['fa-xs', (section.sub_sections[0]?.hovered ? !section.sub_sections[0]?.is_favorite : section.sub_sections[0]?.is_favorite) ? 'fa fa-star' : 'fa-regular fa-star fa-beat-fade']" style="--fa-beat-fade-opacity: 0.67; --fa-beat-fade-scale: 1.075;"></i>
                        </button>
                        <div class="d-flex align-items-center justify-content-start h-100">
                            <i :class="[section?.dom_icon, 'fa-xl']"></i>
                            <a class="mb-0 ms-3 fw-bold text-dark h5" v-text="section.sub_sections[0]?.dom_label" :href="section.sub_sections[0]?.dom_route_url"></a>
                            <a class="ms-auto fs-2" :href="section.sub_sections[0]?.dom_route_url">
                                <i class="fa-solid fa-circle-arrow-right text-info-1"></i>
                            </a>
                        </div>
                    </template>
                    <template v-else>
                        <div class="d-flex align-items-center justify-content-start mb-3">
                            <i :class="[section?.dom_icon, 'fa-xl']"></i>
                            <span class="mb-0 ms-3 fw-bold text-dark h5" v-text="section?.dom_label"></span>
                        </div>
                        <div>
                            <template v-for="(subSection, indexSubSection) in section?.sub_sections" :key="indexSubSection">
                                <div class="d-flex align-items-center justify-content-start ps-2 py-1">
                                    <button type="button"
                                            :class="['badge badge-center rounded-pill border-light', (subSection.hovered ? !subSection.is_favorite : subSection.is_favorite) ? 'bg-warning' : 'bg-secondary']"
                                            data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-original-title="(subSection?.hovered ? !subSection?.is_favorite : subSection?.is_favorite) ? 'Quitar de favoritos' : 'Marcar como favorito'"
                                            @click="changeFavorite(subSection)"
                                            @mouseenter="subSection.hovered = true"
                                            @mouseleave="subSection.hovered = false">
                                        <i :class="['fa-xs', (subSection.hovered ? !subSection.is_favorite : subSection.is_favorite) ? 'fa fa-star' : 'fa-regular fa-star fa-beat-fade']" style="--fa-beat-fade-opacity: 0.67; --fa-beat-fade-scale: 1.075;"></i>
                                    </button>
                                    <a class="text-info-1 fw-semibold px-2" v-text="subSection?.dom_label" :href="subSection?.dom_route_url"></a>
                                </div>
                            </template>
                        </div>
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
                                        store: "Agregar",
                                        update: "Editar"
                                    }
                                }
                            }
                        },
                        data: {},
                        config: {
                            showOnlyFavorites: false
                        },
                        errors: {}
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

                resolve(true);

            });

        },
        // Entity forms
        async changeFavorite(subSection = null) {

            if(this.isDefined({value: subSection?.id})) {

                let form = Utils.cloneJson(subSection);

                const store = await Requests.patch({route: this.config.entity.routes.store, data: form, id: subSection?.id});

                if(Requests.valid({result: store})) {

                    Alerts.toastrs({type: "success", subtitle: store?.data?.msg});

                    this.config.essential.sections = window.sections = store?.data?.sections || [];

                }else {

                    Alerts.toastrs({type: "error", subtitle: store?.data?.msg});

                }

                Alerts.tooltips({show: false});

            }

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [this.config.entity.page];

        },
        sections: function() {

            let filtered = this.config?.essential?.sections.filter(e => !["sc_home"].includes(e?.slug));

            if(this.forms.entity.createUpdate.config.showOnlyFavorites) {

                filtered = filtered.filter(e => e.sub_sections?.some(ss => ss.is_favorite == 1));

            }

            return filtered;

        }
    }
};
</script>
