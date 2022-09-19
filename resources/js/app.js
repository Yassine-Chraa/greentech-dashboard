/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require("./bootstrap");

window.Vue = require("vue").default;

import Vue from "vue";
import VueRouter from "vue-router";
import VueConfirmDialog from "vue-confirm-dialog/src/index";

Vue.use(VueConfirmDialog);
Vue.component("vue-confirm-dialog", VueConfirmDialog.default);
Vue.use(VueRouter);

Vue.component("PageHeader", require("./components/PageHeader").default);
Vue.component("InfoBox", require("./components/InfoBox").default);
Vue.component("Notifications", require("./components/Notifications").default);

const routes = [
  { path: "/home", component: require("./pages/Dashboard.vue").default },
  { path: "/clients", component: require("./pages/Clients.vue").default },
  { path: "/produits", component: require("./pages/Produits.vue").default },
  { path: "/commandes", component: require("./pages/Commandes.vue").default },
  { path: "/users", component: require("./pages/Users.vue").default },
  { path: "/categories", component: require("./pages/Categories.vue").default },
  { path: "/messages", component: require("./pages/Messages.vue").default },
];

const router = new VueRouter({
  mode: "history",
  routes, // short for `routes: routes`
});

const app = new Vue({
  el: "#app",
  router,
});

require("./pure");
