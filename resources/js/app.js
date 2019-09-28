require("./bootstrap");

import Vue from "vue";

import App from "./components/app.vue";

Vue.component("v-app", App);

window.onload = () => {
  new Vue({
    el: "#app",
    template: "<v-app />"
  });
};
