require("./bootstrap");

import Vue from "vue";

import Admin from "./components/admin.vue";

Vue.component("v-admin", Admin);

window.onload = () => {
  new Vue({
    el: "#admin",
    template: "<v-admin />"
  });
};
