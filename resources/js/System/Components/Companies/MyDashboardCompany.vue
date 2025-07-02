<template>
    <div>
        <div class="overflow-auto">
            <div id="myWebCompany" ref="myWebCompany" class="card border border-light">
                <div class="card-header bg-info-1 border-light py-2 d-flex justify-content-center align-items-center">
                    <span class="text-white my-0 fw-bold">Ingresa a mi plataforma</span>
                </div>
                <div class="card-body py-3 pattern" v-if="isDefined({value: config.essential.ownerApp?.assets.img.logomark})">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="p-1 bg-white border border-secondary rounded">
                            <img :src="qrImage" alt="QR" class="img-fluid" style="width: 150px; height: 150px;"/>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-1">
                        <span class="d-block fw-bold" v-text="config.essential.ownerApp?.commercial_name ?? ''"></span>
                    </div>
                </div>
                <div v-else class="card-body py-3">
                    <div class="alert alert-warning mb-1">
                        <i class="fa fa-warning"></i>
                        <span class="ms-2">Sin vista previa.</span>
                    </div>
                    <span class="small">Revise el isotipo.</span>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-center gap-1 gap-md-2 mt-3" v-if="isDefined({value: config.essential.ownerApp?.assets.img.logomark})">
            <button @click="shareResource" class="btn btn-primary btn-sm waves-effect">
                <i class="fa-solid fa-share-nodes"></i>
                <span class="ms-2">Compartir</span>
            </button>
            <button @click="downloadResource" class="btn btn-success btn-sm waves-effect">
                <i class="fa fa-download"></i>
                <span class="ms-2">Descargar</span>
            </button>
        </div>
    </div>
</template>

<script>
import QRCodeStyling from "qr-code-styling";
import html2canvas from "html2canvas";

import { generalConfig } from "../../Helpers/Constants.js";
import { isDefined, getAsset } from "../../Helpers/Utils.js";

export default {
    name: "MyDashboardCompany",
    props: {},
    data() {
        return {
            qrImage: "",
            config: {
                ...generalConfig
            }
        };
    },
    computed: {
        //
    },
    mounted() {

        if(this.isDefined({value: this.config.essential.ownerApp?.assets.img.logomark})) {

            this.generateQR();

        }

    },
    methods: {
        isDefined({value}) {

            return isDefined({value});

        },
        getAsset(path, {type, back}) {

            return Utils.getAsset(path, {type, back});

        },
        generateQR() {

            const qr = new QRCodeStyling({
                width: 300,
                height: 300,
                data: generateMyUrl(this.company, false, "my_dashboard"),
                image: getAsset(this.config.essential.ownerApp?.assets.img.logomark, {type: "none", back: 1}),
                imageOptions: {
                    crossOrigin: "anonymous",
                    hideBackgroundDots: true,
                    imageSize: 0.6,
                    margin: 0
                },
                dotsOptions: { color: "#000000", type: "rounded" },
                backgroundOptions: { color: "#ffffff" }
            });

            qr.getRawData("png")
            .then((blob) => {

                this.qrImage = URL.createObjectURL(blob);

            });

        },
        async shareResource() {

            const original = this.$refs.myWebCompany;
            const cloned = original.cloneNode(true);

            document.body.appendChild(cloned);

            // 2. Esperar a que todas las <img> (logo + QR) estén cargadas
            const imgs = Array.from(cloned.querySelectorAll("img"));
            await Promise.all(imgs.map(img => img.complete ? Promise.resolve() : new Promise(res => { img.onload = img.onerror = res; })));

            // 3. Renderizar con html2canvas a mayor resolución
            const canvas = await html2canvas(cloned, {
                scale: 2,
                useCORS: true,
                allowTaint: false,
                backgroundColor: null
            });

            // 4. Eliminar el clon
            document.body.removeChild(cloned);

            // 5. Compartir
            canvas.toBlob(async blob => {

                const file = new File([blob], "my_dashboard.png", { type: "image/png" });

                if(navigator.canShare && navigator.canShare({ files: [file] })) {

                    try {

                        await navigator.share({title: "my_dashboard", text: this.customer?.name, files: [file]});

                    }catch(err) {

                        console.error("Error al compartir:", err);

                    }

                }else {

                    alert("Tu navegador no soporta compartir archivos.");
                }

            }, "image/png");

        },
        async downloadResource() {

            const original = this.$refs.myWebCompany;
            const cloned = original.cloneNode(true);

            cloned.style.width  = "300px";
            cloned.style.height = "auto";

            document.body.appendChild(cloned);

            const imgs = Array.from(cloned.querySelectorAll("img"));

            await Promise.all(imgs.map(img => img.complete ? Promise.resolve() : new Promise(res => { img.onload = img.onerror = res; })));

            const canvas = await html2canvas(cloned, {
                scale: 2,
                useCORS: true,
                allowTaint: false,
                backgroundColor: null
            });

            document.body.removeChild(cloned);

            const link = document.createElement("a");
            link.href = canvas.toDataURL("image/png");
            link.download = `my_dashboard.png`;
            link.click();

        }
    },
    watch: {
        customer: {
            deep: true,
            handler() {

                this.generateQR();

            }
        }
    }
};
</script>

<style scoped>
.pattern {
    background-image: linear-gradient(
        45deg,
        rgba(13,110,253,0.05) 0px,
        rgba(13,110,253,0.05) 5px,
        rgba(255,255,255,1) 5px,
        rgba(255,255,255,1) 10px
    ) !important;
    background-size: 10px 10px !important;
    background-repeat: repeat !important;
}
</style>
