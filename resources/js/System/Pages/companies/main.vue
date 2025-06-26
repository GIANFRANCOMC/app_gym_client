<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="nav-align-top">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-general" aria-controls="navs-pills-general" aria-selected="false" tabindex="-1">
                    <span class="d-flfex align-items-center fw-bold">
                        <span>🏢</span>
                        <span class="ms-2">Información general</span>
                    </span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-contacts" aria-controls="navs-pills-contacts" aria-selected="false" tabindex="-1">
                    <span class="d-flex align-items-center fw-bold">
                        <span>☎️</span>
                        <span class="ms-2">Información de contacto</span>
                    </span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-socials-media" aria-controls="navs-pills-socials-media" aria-selected="false" tabindex="-1">
                    <span class="d-flex align-items-center fw-bold">
                        <span>📱</span>
                        <span class="ms-2">Cuentas sociales</span>
                    </span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-branding" aria-controls="navs-pills-branding" aria-selected="false" tabindex="-1">
                    <span class="d-flfex align-items-center fw-bold">
                        <span>🎨</span>
                        <span class="ms-2">Identidad visual</span>
                    </span>
                </button>
            </li>
            <li class="nav-item" role="presentation" v-show="false">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-configuration" aria-controls="navs-pills-configuration" aria-selected="false" tabindex="-1">
                    <span class="d-flex align-items-center fw-bold">
                        <span>⚙️</span>
                        <span class="ms-2">Parámetros</span>
                    </span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-share" aria-controls="navs-pills-share" aria-selected="false" tabindex="-1">
                    <span class="d-flex align-items-center fw-bold">
                        <span>🔗</span>
                        <span class="ms-2">Accesos compartidos</span>
                    </span>
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade" id="navs-pills-general" role="tabpanel">
                <div class="row g-3">
                    <InputSlot
                        hasDiv
                        title="Tipo de documento"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.identity_document_type"
                        xl="6"
                        lg="6">
                        <template v-slot:input>
                            <v-select
                                v-model="forms.entity.createUpdate.data.identity_document_type"
                                :options="identityDocumentTypes"
                                :clearable="false"
                                :searchable="false"/>
                        </template>
                    </InputSlot>
                    <InputText
                        v-model="forms.entity.createUpdate.data.document_number"
                        hasDiv
                        title="Número de documento"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.document_number"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.legal_name"
                        hasDiv
                        title="Nombre legal"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.legal_name"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.commercial_name"
                        hasDiv
                        title="Nombre comercial"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.commercial_name"
                        xl="6"
                        lg="6"/>
                    <InputSlot
                        v-if="false"
                        hasDiv
                        title="Estado"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.status"
                        xl="6"
                        lg="6">
                        <template v-slot:input>
                            <v-select
                                v-model="forms.entity.createUpdate.data.status"
                                :options="statuses"
                                :clearable="false"
                                :disabled="true"/>
                        </template>
                    </InputSlot>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-pills-contacts" role="tabpanel">
                <div class="row g-3">
                    <InputText
                        v-model="forms.entity.createUpdate.data.address"
                        hasDiv
                        title="Dirección"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.address"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.telephone"
                        hasDiv
                        title="Teléfono"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.telephone"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.email"
                        hasDiv
                        title="Correo electrónico"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.email"
                        xl="6"
                        lg="6"/>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-pills-socials-media" role="tabpanel">
                <div class="row g-3">
                    <InputText
                        v-model="forms.entity.createUpdate.data.facebook"
                        hasDiv
                        title="Facebook"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.facebook"
                        xl="6"
                        lg="6">
                        <template v-slot:inputGroupAppend>
                            <a :href="forms.entity.createUpdate.data.facebook" target="_blank" class="btn btn-label-info waves-effect" v-if="isDefined({value: forms.entity.createUpdate.data.facebook}) && isValidUrl({url: forms.entity.createUpdate.data.facebook})">
                                <span>Visitar</span>
                            </a>
                        </template>
                    </InputText>
                    <InputText
                        v-model="forms.entity.createUpdate.data.instagram"
                        hasDiv
                        title="Instagram"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.instagram"
                        xl="6"
                        lg="6">
                        <template v-slot:inputGroupAppend>
                            <a :href="forms.entity.createUpdate.data.instagram" target="_blank" class="btn btn-label-danger waves-effect" v-if="isDefined({value: forms.entity.createUpdate.data.instagram}) && isValidUrl({url: forms.entity.createUpdate.data.instagram})">
                                <span>Visitar</span>
                            </a>
                        </template>
                    </InputText>
                    <InputText
                        v-model="forms.entity.createUpdate.data.whatsapp"
                        hasDiv
                        title="Whatsapp"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.whatsapp"
                        xl="6"
                        lg="6">
                        <template v-slot:inputGroupAppend>
                            <a :href="forms.entity.createUpdate.data.whatsapp" target="_blank" class="btn btn-label-success waves-effect" v-if="isDefined({value: forms.entity.createUpdate.data.whatsapp}) && isValidUrl({url: forms.entity.createUpdate.data.whatsapp})">
                                <span>Visitar</span>
                            </a>
                        </template>
                    </InputText>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-pills-branding" role="tabpanel">
                <div class="row g-3">
                    <InputText
                        v-model="forms.entity.createUpdate.data.tagline"
                        hasDiv
                        title="Slogan"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.tagline"
                        xl="12"
                        lg="12"/>
                    <InputTextArea
                        v-model="forms.entity.createUpdate.data.description"
                        hasDiv
                        title="Descripción general"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.description"
                        xl="12"
                        lg="12"/>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center align-middle">
                                <th class="bg-secondary text-white fw-semibold" style="width: 60%;">NOMBRE DEL RECURSO</th>
                                <th class="bg-secondary text-white fw-semibold" style="width: 40%;">IMAGEN</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0 bg-white">
                            <tr class="text-center">
                                <td class="text-start">
                                    <InputSlot
                                        :hasDiv="false"
                                        title="Logotipo">
                                        <template v-slot:input>
                                            <input type="file" class="form-control" id="logotypeFileId" accept="image/png, image/jpg, image/jpeg"/>
                                        </template>
                                    </InputSlot>
                                    <div class="text-muted d-block mt-1">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="ms-2 small">Versión textual del nombre de la marca. Debe contener solo letras, sin símbolos.</span>
                                    </div>
                                </td>
                                <td>
                                    <img v-if="isDefined({value: forms.entity.createUpdate.data.logotype})" :src="getAsset(forms.entity.createUpdate.data.logotype)" width="150px" height="150px" class="img-fluid"/>
                                    <div v-else>
                                        <img :src="config.essential.ownerApp.assets.img.logotype" width="150px" height="150px" class="img-fluid"/>
                                        <div class="alert alert-warning w-100 py-1">Es referencial</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class="text-start">
                                    <InputSlot
                                        :hasDiv="false"
                                        title="Marca combinada">
                                        <template v-slot:input>
                                            <input type="file" class="form-control" id="combinationmarkFileId" accept="image/png, image/jpg, image/jpeg"/>
                                        </template>
                                    </InputSlot>
                                    <div class="text-muted d-block mt-1">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="ms-2 small">Combinación del logotipo y el isotipo en una sola unidad visual.</span>
                                    </div>
                                </td>
                                <td>
                                    <img v-if="isDefined({value: forms.entity.createUpdate.data.combinationmark})" :src="getAsset(forms.entity.createUpdate.data.combinationmark)" width="150px" height="150px" class="img-fluid"/>
                                    <div v-else>
                                        <img :src="config.essential.ownerApp.assets.img.combinationmark" width="150px" height="150px" class="img-fluid"/>
                                        <div class="alert alert-warning w-100 py-1">Es referencial</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class="text-start">
                                    <InputSlot
                                        :hasDiv="false"
                                        title="Isotipo">
                                        <template v-slot:input>
                                            <input type="file" class="form-control" id="logomarkFileId" accept="image/png, image/jpg, image/jpeg"/>
                                        </template>
                                    </InputSlot>
                                    <div class="text-muted d-block mt-1">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="ms-2 small">Ícono o símbolo gráfico que representa la marca sin texto.</span>
                                    </div>
                                </td>
                                <td>
                                    <img v-if="isDefined({value: forms.entity.createUpdate.data.logomark})" :src="getAsset(forms.entity.createUpdate.data.logomark)" width="150px" height="150px" class="img-fluid"/>
                                    <div v-else>
                                        <img :src="config.essential.ownerApp.assets.img.logomark" width="150px" height="150px" class="img-fluid"/>
                                        <div class="alert alert-warning w-100 py-1">Es referencial</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class="text-start">
                                    <InputSlot
                                        :hasDiv="false"
                                        title="Imagen de login">
                                        <template v-slot:input>
                                            <input type="file" class="form-control" id="loginImageFileId" accept="image/png, image/jpg, image/jpeg"/>
                                        </template>
                                    </InputSlot>
                                    <div class="text-muted d-block mt-1">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="ms-2 small">Imagen que se muestra en la pantalla de inicio de sesión. Generalmente puede ser un logo, una ilustración institucional o una imagen decorativa que represente a la entidad.</span>
                                    </div>
                                </td>
                                <td>
                                    <img v-if="isDefined({value: forms.entity.createUpdate.data.login_image})" :src="getAsset(forms.entity.createUpdate.data.login_image)" width="150px" height="150px" class="img-fluid"/>
                                    <div v-else>
                                        <img :src="config.essential.ownerApp.assets.img.login_image" width="150px" height="150px" class="img-fluid"/>
                                        <div class="alert alert-warning w-100 py-1">Es referencial</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-pills-configuration" role="tabpanel" v-show="false">
                <div class="row g-3">
                    <InputText
                        v-model="forms.entity.createUpdate.data.slug"
                        hasDiv
                        title="Acceso MI WEB"
                        isRequired
                        :disabled="true"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.slug"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.token_api_misc"
                        hasDiv
                        title="Token API - Misc"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.token_api_misc"
                        xl="6"
                        lg="6"/>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-pills-share" role="tabpanel">
                <div class="row g-3">
                    <InputSlot
                        hasDiv
                        :isInputGroup="false"
                        :divInputClass="['d-flex justify-content-center']"
                        xl="6"
                        lg="6">
                        <template v-slot:input>
                            <MyDashboardCompany/>
                        </template>
                    </InputSlot>
                    <InputSlot
                        hasDiv
                        :isInputGroup="false"
                        :divInputClass="['d-flex justify-content-center']"
                        xl="6"
                        lg="6">
                        <template v-slot:input>
                            <MyWebCompany/>
                        </template>
                    </InputSlot>
                </div>
            </div>
            <div class="mt-4" v-if="isDefined({value: forms.entity.createUpdate.data.id})">
                <div class="row g-3">
                    <div class="d-flex flex-row-reverse">
                        <button type="button" class="btn waves-effect btn-primary" @click="createUpdateEntity()">
                            <i class="fa fa-save"></i>
                            <span class="ms-2">Guardar información</span>
                        </button>
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

        Utils.navbarItem("menu-item-configuration", {addClass: "open"});
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
                        data: {
                            id: null,
                            slug: "",
                            identity_document_type: null,
                            document_number: "",
                            legal_name: "",
                            commercial_name: "",
                            tagline: "",
                            description: "",
                            address: "",
                            telephone: "",
                            email: "",
                            token_api_misc: "",
                            logotype: "",
                            combinationmark: "",
                            logomark: "",
                            login_image: "",
                            facebook: "",
                            instagram: "",
                            whatsapp: "",
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
                    ...Requests.config({entity: "companies"}),
                    page: {
                        title: "Mi empresa",
                        active: true,
                        menu: {
                            id: "menu-item-configuration-companies"
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

            this.options.companies             = initParams.data?.config?.companies;
            this.options.company               = initParams.data?.config?.company;
            this.options.identityDocumentTypes = initParams.data?.config?.identityDocumentTypes;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                const company = (this.options.company?.records ?? []).length > 0 ? this.options.company?.records[0] : null;

                let identityDocumentType = this.identityDocumentTypes.filter(e => e.code === company?.identity_document_type_id)[0],
                    status               = this.statuses.filter(e => e.code === company?.status)[0];

                this.forms.entity.createUpdate.data.id                     = company?.id;
                this.forms.entity.createUpdate.data.slug                   = company?.slug;
                this.forms.entity.createUpdate.data.identity_document_type = identityDocumentType;
                this.forms.entity.createUpdate.data.document_number        = company?.document_number;
                this.forms.entity.createUpdate.data.legal_name             = company?.legal_name;
                this.forms.entity.createUpdate.data.commercial_name        = company?.commercial_name;
                this.forms.entity.createUpdate.data.tagline                = company?.tagline;
                this.forms.entity.createUpdate.data.description            = company?.description;
                this.forms.entity.createUpdate.data.address                = company?.address;
                this.forms.entity.createUpdate.data.telephone              = company?.telephone;
                this.forms.entity.createUpdate.data.email                  = company?.email;
                this.forms.entity.createUpdate.data.token_api_misc         = company?.token_api_misc;
                this.forms.entity.createUpdate.data.logotype               = company?.logotype;
                this.forms.entity.createUpdate.data.combinationmark        = company?.combinationmark;
                this.forms.entity.createUpdate.data.logomark               = company?.logomark;
                this.forms.entity.createUpdate.data.login_image            = company?.login_image;
                this.forms.entity.createUpdate.data.facebook               = company?.facebook;
                this.forms.entity.createUpdate.data.instagram              = company?.instagram;
                this.forms.entity.createUpdate.data.whatsapp               = company?.whatsapp;
                this.forms.entity.createUpdate.data.status                 = status;

                const tabTrigger = document.querySelector('[data-bs-target="#navs-pills-general"]');
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();

                resolve(true);

            });

        },
        // Forms
        async createUpdateEntity() {

            const functionName = "createUpdateEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.createUpdate.data);

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive"}});

            if(validateForm?.bool) {

                form.identity_document_type_id = form?.identity_document_type?.code;
                form.status = form?.status?.code;

                delete form.identity_document_type;

                let formData = new FormData();

                for(let key in form) {

                    if(form.hasOwnProperty(key)) {

                        formData.append(key, form[key] ?? "");

                    }

                }

                const logotypeFile        = document.getElementById("logotypeFileId");
                const combinationmarkFile = document.getElementById("combinationmarkFileId");
                const logomarkFile        = document.getElementById("logomarkFileId");
                const loginImageFile      = document.getElementById("loginImageFileId");

                if(logotypeFile && logotypeFile.files && logotypeFile.files.length > 0) {

                    formData.append("logotype", logotypeFile.files[0]);

                }

                if(combinationmarkFile && combinationmarkFile.files && combinationmarkFile.files.length > 0) {

                    formData.append("combinationmark", combinationmarkFile.files[0]);

                }

                if(logomarkFile && logomarkFile.files && logomarkFile.files.length > 0) {

                    formData.append("logomark", logomarkFile.files[0]);

                }

                if(loginImageFile && loginImageFile.files && loginImageFile.files.length > 0) {

                    formData.append("login_image", loginImageFile.files[0]);

                }

                let createUpdate = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.update, formData: formData, id: form.id}) :
                                                                             Requests.post({route: this.config.entity.routes.store, formData: formData}));

                if(Requests.valid({result: createUpdate})) {

                    this.forms.entity.createUpdate.data.logotype        = createUpdate.data.company?.logotype;
                    this.forms.entity.createUpdate.data.combinationmark = createUpdate.data.company?.combinationmark;
                    this.forms.entity.createUpdate.data.logomark        = createUpdate.data.company?.logomark;
                    this.forms.entity.createUpdate.data.login_image     = createUpdate.data.company?.login_image;

                    // Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.default.id});
                    // Alerts.toastrs({type: "success", subtitle: createUpdate?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "success", msgContent: createUpdate?.data?.msg});

                    this.clearForm({functionName});

                }else {

                    this.formErrors({functionName, type: "set", errors: createUpdate?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functioname, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                //Alerts.swals({show: false});
                Alerts.generateAlert({messages: Utils.getErrors({errors: validateForm}), keys: [{"column": "section", "label" : "Sección"}, {"column": "msg", "label" : "Mensaje"}], msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`, width: 800});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    document.getElementById("logotypeFileId").value = "";
                    document.getElementById("combinationmarkFileId").value = "";
                    document.getElementById("logomarkFileId").value = "";
                    document.getElementById("loginImageFileId").value = "";
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

                result.slug                   = [];
                result.identity_document_type = [];
                result.document_number        = [];
                result.legal_name             = [];
                result.commercial_name        = [];
                result.address                = [];
                result.telephone              = [];
                result.email                  = [];
                result.token_api_misc         = [];
                result.status                 = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.slug})) {

                    result.slug.push({section: "Mi empresa" , msg: `${isDescriptive ? "Slug:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.identity_document_type})) {

                    result.identity_document_type.push({section: "Mi empresa", msg: `${isDescriptive ? "Tipo de documento:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.document_number})) {

                    result.document_number.push({section: "Mi empresa", msg: `${isDescriptive ? "Número de documento:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.legal_name})) {

                    result.legal_name.push({section: "Mi empresa", msg: `${isDescriptive ? "Nombre legal:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.commercial_name})) {

                    result.commercial_name.push({section: "Mi empresa", msg: `${isDescriptive ? "Nombre comercial:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                }

                /* if(!this.isDefined({value: form?.address})) {

                    result.address.push({section: "Contacto", msg: `${isDescriptive ? "Dirección:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                } */

                /* if(!this.isDefined({value: form?.telephone})) {

                    result.telephone.push({section: "Contacto", msg: `${isDescriptive ? "Teléfono:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                } */

                if(!this.isDefined({value: form?.status})) {

                    result.status.push({section: "Otro", msg: `${isDescriptive ? "Estado:" : ""} ${this.config.forms.errors.labels.required}`});
                    result.bool = false;

                }

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        fixedNumber(value) {

            return Utils.fixedNumber(value);

        },
        isValidUrl({url}) {

            return Utils.isValidUrl({url});

        },
        getAsset(path) {

            const baseUrl = "/storage/";

            return `${baseUrl}${path}`;

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [{title: "Configuración"}, this.config.entity.page];

        },
        identityDocumentTypes: function() {

            return this.options?.identityDocumentTypes?.records.map(e => ({code: e.id, label: e.name}));

        },
        statuses: function() {

            return this.options?.companies?.statuses.map(e => ({code: e.code, label: e.label}));

        }
    }
};
</script>
