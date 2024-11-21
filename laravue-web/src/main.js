//import "bootstrap/dist/css/bootstrap.min.css";
import "./assets/css/styles.min.css";
import "vue-toastification/dist/index.css";
import "./assets/css/fontawesome.min.css";

import App from "./App.vue";
import Toast from "vue-toastification";
import { createApp } from "vue";
import { createPinia } from "pinia";
import { directiveCan } from "./helpers";
import router from "./router";

const app = createApp(App);

app.use(createPinia());
app.directive("can", directiveCan);
app.use(router);
app.use(Toast, { hideProgressBar: false });
app.mount("#app");
