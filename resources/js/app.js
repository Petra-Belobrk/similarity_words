require('./bootstrap');


import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router';
import {routes} from './routes';
/* import store from './store/store'; */


window.Vue = require('vue');
Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});


const app = new Vue({
    el: '#app',
    router,
    /* store, */
    components: {
        "app":App,
    },
});