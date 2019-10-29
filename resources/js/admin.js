require("./bootstrap");

import Vue from "vue";

window.onload = () => {
  new Vue({
    el: "#admin",
    template: "<v-admin />"
  });
};
