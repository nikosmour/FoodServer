/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap';
import {createApp} from 'vue';
import router from './router';
import App from './Pages/App.vue';
import store from './store/auth';
import {route} from '../../vendor/tightenco/ziggy';
import vuetify from './plugins/vuetify';
import {FiltersPlugin} from './plugins/filters.js';
import {Ziggy} from './plugins/ziggy.js';
import {EnumPlugin} from './plugins/enums.js';
import i18n from './plugins/i18n.js';
// import {ZiggyVue} from '../../vendor/tightenco/ziggy';

/**
 * The following block of create a request to the server to receive if
 * exist the authenticated user
 */

let promise = null;
console.log(Date.now() % 10000, 'after')
if (window.isAuthenticated) {
    promise = store.dispatch('getUser');
    console.log(Date.now() % 10000, 'dispatch get User');
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = createApp(App);

/**
 * The following block of code may be used to automatically register your
 * Vue components of Components folder. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./Components/ExampleComponent.vue -> <example-component></example-component>
 */

const requireComponent = import.meta.glob('./Components/**/*.vue');
// Register each component globally
for (const [fileName, component] of Object.entries(requireComponent)) {
    component().then((componentConfig) => {
        const componentName = fileName
            .split('/')
            .pop()
            .replace(/\.\w+$/, ''); // Remove the file extension

        app.component(componentName, componentConfig.default || componentConfig);
    });
}

// Use any plugins
app.use(i18n);
app.use(EnumPlugin);
app.use(store);
app.use(FiltersPlugin);
app.use(vuetify);

// app.use(ZiggyVue)//,Ziggy);
window.route = route;
window.Ziggy = Ziggy;
app.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    }
});

/**
 * The following block will waiting  the request to the server to
 * finished and  to register the authenticated user
 */
if (promise) {
    console.log(Date.now() % 10000, ' app mount');
    await promise;
    console.log(Date.now() % 10000, ' app mount 2');
}
/**
 * after we  will have the user we can load  the router to determent
 * in which page will  direct or redirect
 */
app.use(router);

// Mount the app to the DOM
app.mount('#app');
console.log(Date.now() % 10000, ' app mount 3');
