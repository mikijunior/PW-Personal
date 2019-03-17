/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import App from './components/App.vue';
import axios from 'axios';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import Register from './components/RegisterComponent.vue';
import Login from './components/LoginComponent.vue';
import Personal from './components/PersonalComponent.vue';
import SendResetLink from './components/SendResetLinkComponent';
import ChangePassword from './components/ChangePasswordComponent';
import ResetPassword from './components/ResetPasswordComponent';
import VeeValidate, { Validator }  from 'vee-validate';
import { VueReCaptcha } from 'vue-recaptcha-v3';
import ru from 'vee-validate/dist/locale/ru';
import en from 'vee-validate/dist/locale/en';
import './plugins/ml';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(VueReCaptcha, { siteKey: process.env.MIX_CAPTCHA_SITE_KEY });
Vue.use(VeeValidate);
axios.defaults.headers.appLocale = localStorage.getItem('lang') || 'ru';
axios.defaults.baseURL = 'http://localhost:8000/api';
if(axios.defaults.headers.appLocale === 'en') {
    Validator.localize('en', en);
} else {
    Validator.localize('ru', ru);
}

const router = new VueRouter({
    mode: 'history',
    routes: [{
        path: '/',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },{
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },{
        path: '/personal',
        name: 'personal',
        component: Personal,
        meta: {
            auth: true
        }
    },{
        path: '/personal/change-password',
        name: 'changePassword',
        component: ChangePassword,
        meta: {
            auth: true
        }
    },{
        path: '/reset-password',
        name: 'resetPassword',
        component: SendResetLink,
        meta: {
            auth: false
        }
    },{
        path: '/password/reset/:token',
        name: 'resetForm',
        component: ResetPassword,
        meta: {
            auth: false
        }
    }]
});

Vue.router = router;
Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router;
new Vue(App).$mount('#app');