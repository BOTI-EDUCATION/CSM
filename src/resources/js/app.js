require('./bootstrap');

window.Vue = require('vue').default;
import router from './router';
// import Layout from './components/Layout.vue'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueTreeList from 'vue-tree-list'
import Vue from 'vue';
import SmartTable from 'vuejs-smart-table';
import {BootstrapVue} from 'bootstrap-vue';
import DateRangePicker from 'vue2-daterange-picker';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'


// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.component('app', require('./components/App.vue').default);
Vue.use(VueSweetalert2);
Vue.use(VueTreeList);
Vue.use(SmartTable);
Vue.use(BootstrapVue);
Vue.use(DateRangePicker);
const app = new Vue({
    el: '#app',
    router
});
