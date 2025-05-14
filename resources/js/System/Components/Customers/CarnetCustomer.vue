<template>
    <div>
        <div class="overflow-auto">
            <div id="carnet" ref="carnet" class="card carnet-card mx-auto shadow-sm position-relative overflow-hidden border border-light shadow" style="min-width: 450px;">
                <!-- <img :src="fallbackUrl" class="watermark-logo img-fluid" style="width: 22%;" @error="onImageError($event)"/> -->
                <div class="card-header bg-dark border-light py-2 d-flex justify-content-center align-items-center">
                    <span class="text-white my-0 fw-bold" v-text="header"></span>
                </div>
                <div class="card-body d-flex align-items-center p-3 carnet-body pattern">
                    <div class="flex-shrink-0 p-1 bg-white border border-secondary rounded">
                        <img :src="qrImage" alt="QR" class="img-fluid" style="width: 100px; height: 100px;" />
                    </div>
                    <div class="flex-grow-1 ms-4">
                        <p class="h5 text-dark mb-1" v-text="customer?.name"></p>
                        <p class="text-secondary fw-bold mb-0">N° {{ customer?.document_number }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-center gap-2 gap-md-3 mt-3">
            <button @click="compartirCarnet" class="btn btn-primary waves-effect">
                <i class="fa-solid fa-share-nodes"></i>
                <span class="ms-2">Compartir</span>
            </button>
            <button @click="descargarCarnet" class="btn btn-success waves-effect">
                <i class="fa fa-download"></i>
                <span class="ms-2">Descargar</span>
            </button>
        </div>
    </div>
</template>

<script>
import QRCodeStyling from "qr-code-styling";
import html2canvas from "html2canvas";

import { encodeBase64UTF8 } from "../../Helpers/Utils.js";

export default {
    name: "CarnetCustomer",
    props: {
        customer: {
            type: Object,
            required: false
        }
    },
    data() {
        return {
            qrImage: "",
            fallbackUrl: "/System/assets/img/utils/owner_app/logomark.png"
        };
    },
    computed: {
        header() {

            return "Carnet / "+(window?.company?.commercial_name ?? "");

        }
    },
    mounted() {
        this.generarQR();
    },
    methods: {
        onImageError(event) {

            event.target.src = this.fallbackUrl;

        },
        generarQR() {

            const dataJson = {
                bp: encodeBase64UTF8(JSON.stringify({id: this.customer?.id ?? ""})),
                identificator: this.customer?.document_number ?? "",
                name: this.customer?.name ?? ""
            };

            const qr = new QRCodeStyling({
                width: 300,
                height: 300,
                data: JSON.stringify(dataJson),
                dotsOptions: { color: "#000000", type: "rounded" },
                backgroundOptions: { color: "#ffffff" },
            });

            qr.getRawData("png")
            .then((blob) => {

                this.qrImage = URL.createObjectURL(blob);

            });

        },
        async compartirCarnet() {

            const original = this.$refs.carnet;
            const cloned = original.cloneNode(true);

            document.body.appendChild(cloned);

            // 2. Esperar a que todas las <img> (logo + QR) estén cargadas
            const imgs = Array.from(cloned.querySelectorAll('img'));
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

                const file = new File([blob], 'carnet.png', { type: 'image/png' });

                if(navigator.canShare && navigator.canShare({ files: [file] })) {

                    try {

                        await navigator.share({title: 'Carnet Digital', text: this.customer?.name, files: [file]});

                    }catch(err) {

                        console.error('Error al compartir:', err);

                    }

                }else {

                    alert('Tu navegador no soporta compartir archivos.');
                }

            }, 'image/png');

        },
        async descargarCarnet() {

            const original = this.$refs.carnet;
            const cloned = original.cloneNode(true);

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
            link.download = `carnet_${this.customer?.document_number}.png`;
            link.click();

        }
    },
    watch: {
        customer: {
            deep: true,
            handler() {

                this.generarQR();

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

.carnet-card {
    max-width: 450px;
    background-color: #ffffff;
}

.carnet-body {
    position: relative;
    z-index: 2;
}

.watermark {
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 1rem;
    z-index: 1;
}

.watermark-logo {
    position: absolute;
    bottom: 14px;
    right: 10px;
    z-index: 5;
    pointer-events: none;
}
</style>
