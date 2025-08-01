import { createApp } from "vue"

// Components imports
import App from "./main.vue"
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import Breadcrumb   from "../../Components/Breadcrumb.vue";
import InputDate    from "../../Components/InputDate.vue";
import InputNumber  from "../../Components/InputNumber.vue";
import InputSelect  from "../../Components/InputSelect.vue";
import InputSlot    from "../../Components/InputSlot.vue";
import InputSelect2 from "../../Components/InputSelect2.vue";
import InputText    from "../../Components/InputText.vue";
import InputTextArea    from "../../Components/InputTextArea.vue";
import Paginator    from "../../Components/Paginator.vue";
import Loader       from "../../Components/Loader.vue";
import WithoutData  from "../../Components/WithoutData.vue";

// App creation and mounted
createApp(App)
.component("v-select", VueSelect)
.component("Breadcrumb", Breadcrumb)
.component("InputDate", InputDate)
.component("InputNumber", InputNumber)
.component("InputSelect", InputSelect)
.component("InputSlot", InputSlot)
.component("InputSelect2", InputSelect2)
.component("InputText", InputText)
.component("InputTextArea", InputTextArea)
.component("Paginator", Paginator)
.component("Loader", Loader)
.component("WithoutData", WithoutData)
.mount("#app");
