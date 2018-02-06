
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


 /*Category*/
Vue.component('category-view', require('./components/category/CategoryView.vue'));
Vue.component('category-trash-view', require('./components/categoryTrash/CategoryTrashView.vue'));

/*Item*/
Vue.component('item-view', require('./components/item/ItemView.vue'));
Vue.component('item-trash-view', require('./components/item/ItemTrashView.vue'));

/*Location*/
Vue.component('location-view', require('./components/location/LocationView.vue'));
const app = new Vue({
    el: '#app'
});
