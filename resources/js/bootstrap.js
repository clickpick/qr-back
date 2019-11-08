window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");
// window.axios.defaults.baseURL = "https://qr-game.ezavalishin.ru";
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const BootstrapToProto = {
  install(Vue) {
    Vue.prototype.$axios = window.axios;
  }
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

import Vue from "vue";

Vue.use(BootstrapToProto);

import ElementUI from "element-ui";
import locale from "element-ui/lib/locale/lang/ru-RU";
import "element-ui/lib/theme-chalk/index.css";

Vue.use(ElementUI, { locale });

import { extend, ValidationProvider } from "vee-validate";
import * as rules from "vee-validate/dist/rules";
import ru from "vee-validate/dist/locale/ru";

// loop over all rules
for (const rule in rules) {
  extend(rule, {
    ...rules[rule], // add the rule
    message: ru.messages[rule] // add its message
  });
}

Vue.component("v-provider", ValidationProvider);

import VueCompositionApi from "@vue/composition-api";

Vue.use(VueCompositionApi);

import VUpload from "./components/upload.vue";

Vue.component("v-upload", VUpload);

import VForm from "./components/form.vue";

Vue.component("v-form", VForm);

import Admin from "./components/admin.vue";

Vue.component("v-admin", Admin);
