import "./app.css";
import { createApp } from 'vue';
import App from "@/App.vue";
import naive from "naive-ui";
import router from "@/routes/routes";
var app = createApp(App);
app.use(naive);
app.use(router);
app.mount('#app');
//# sourceMappingURL=app.js.map