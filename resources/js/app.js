/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
console.log(Date.now() % 10000, 'before bootstrap')
require('./bootstrap');
console.log(Date.now() % 10000, 'after')
import store from './store/auth'; // Import your store
import {FiltersPlugin} from './vueFilters'; // Adjust the path as needed
// window.Vue = require('vue').default;
// Import createApp function from Vue 3
import {createApp} from 'vue';
import router from './router';
// Import any additional plugins or components you may need
import {route} from '../../vendor/tightenco/ziggy';
// import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import {Ziggy} from './ziggy.js';
import {EnumPlugin} from './enums';
import App from './Pages/App.vue'
import {createI18n} from 'vue-i18n';
import en from './locales/en.json';
import el from './locales/el.json';
import enValidation from './locales/en/validation.json';
import elValidation from './locales/el/validation.json';

let promise = null;
console.log(Date.now() % 10000, 'after')
if (window.isAuthenticated) {
    promise = store.dispatch('getUser');
    console.log(Date.now() % 10000, 'dispatch get User');
}

const app = createApp(App);
// Function to determine the locale from browser settings
const getPreferredLocale = () => {
    const browserLocales = navigator.languages || [navigator.language];
    for (const locale of browserLocales) {
        if (['en', 'el'].includes(locale)) {
            return locale; // Return the first supported locale
        }
    }
    return 'en'; // Default to English if no match
};
const i18n = createI18n({
    locale: getPreferredLocale(), // set locale
    fallbackLocale: 'en', // set fallback locale
    messages: {
        en: {
            validation: enValidation,
            ...en
        },
        el: {
            validation: elValidation,
            ...el
        },
    }
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const requireComponent = require.context('./', true, /\.vue$/i)
// Register each component globally
requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName);
    const componentName = fileName
        .split('/')
        .pop()
        .replace(/\.\w+$/, ''); // Remove the file extension

    app.component(componentName, componentConfig.default || componentConfig);
});
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('coupons-purchase-form', require('./components/CouponsPurchaseForm').default);
// Vue.component('entry-checking-form', require('./components/EntryCheckingForm').default);
// Vue.component('export-statistics-form', require('./components/ExportStatisticsForm').default);
// Vue.component('entry-checking', require('./Pages/EntryChecking').default);
// Vue.component('coupon-purchase', require('./Pages/CouponPurchase').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
// });

// Use any plugins
app.use(i18n);
app.use(EnumPlugin);
app.use(store);
app.use(FiltersPlugin);
// app.use(ZiggyVue)//,Ziggy);
window.route = route;
window.Ziggy = Ziggy;
app.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    }
});
if (promise) {
    console.log(Date.now() % 10000, ' app mount');
    await promise;
    console.log(Date.now() % 10000, ' app mount 2');
}
app.use(router);
// Mount the app to the DOM
app.mount('#app');
console.log(Date.now() % 10000, ' app mount 3');
