<template>
    <div>
        <div class="overflow-auto">
            <div id="myWebCompany" ref="myWebCompany" class="card border border-light">
                <div class="card-header bg-success border-light py-2 d-flex justify-content-center align-items-center">
                    <span class="text-white my-0 fw-bold" v-text="header"></span>
                </div>
                <div class="card-body py-3 pattern">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="p-1 bg-white border border-secondary rounded">
                            <img :src="qrImage" alt="QR" class="img-fluid" style="width: 150px; height: 150px;"/>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-1">
                        <span class="d-block fw-bold" v-text="footer"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-center gap-1 gap-md-2 mt-3">
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

export default {
    name: "MyWebCompany",
    props: {},
    data() {
        return {
            qrImage: ""
        };
    },
    computed: {
        header() {

            return "Ingresa a mi página";

        },
        footer() {

            return window?.company?.commercial_name ?? "";

        }
    },
    mounted() {

        this.generateQR();

    },
    methods: {
        generateQR() {

            const qr = new QRCodeStyling({
                width: 300,
                height: 300,
                data: generateMyUrl(window.company, false, "my_web"),
                image: "/storage/"+window.company.logotype,
                imageOptions: {
                    crossOrigin: "anonymous",
                    hideBackgroundDots: true,
                    imageSize: 0.4,
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

                const file = new File([blob], 'my_web.png', { type: 'image/png' });

                if(navigator.canShare && navigator.canShare({ files: [file] })) {

                    try {

                        await navigator.share({title: 'my_web', text: this.customer?.name, files: [file]});

                    }catch(err) {

                        console.error('Error al compartir:', err);

                    }

                }else {

                    alert('Tu navegador no soporta compartir archivos.');
                }

            }, 'image/png');

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
            link.download = `my_web.png`;
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
