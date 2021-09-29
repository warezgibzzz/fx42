import Vue from "vue"
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueCompositionAPI from "@vue/composition-api";
import './styles/app.css'
import {FontAwesomeIcon, FontAwesomeLayers, FontAwesomeLayersText} from "@fortawesome/vue-fontawesome";

Vue.use(VueCompositionAPI);

Vue.component('FAIcon', FontAwesomeIcon)
Vue.component('FALayers', FontAwesomeLayers)
Vue.component('FALayersText', FontAwesomeLayersText)

const app = createApp(App)
app.use(router)
app.use(store)

app.mount("#app");