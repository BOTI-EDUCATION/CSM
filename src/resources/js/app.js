require('./bootstrap');

window.Vue = require('vue').default;
import router from './router';
import Layout from './components/Layout.vue'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueTreeList from 'vue-tree-list'

Vue.component('app', require('./components/App.vue').default);
Vue.use(VueSweetalert2);
Vue.use(VueTreeList)

const app = new Vue({
    el: '#app',
    router
});
