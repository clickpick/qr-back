require("./bootstrap");

import Vue from "vue";

window.onload = () => {
  new Vue({
    el: "#app",
    template: "<v-app />"
  });
};
