require('./bootstrap');

window.Vue = require('vue').default;
import Swal from 'sweetalert2';
import VueProgressBar from 'vue-progressbar';


// For VueProgressBar

Vue.use(VueProgressBar, {
    color: '#3490dc',
    failedColor: 'red',
    // height: '2px',
    thickness: '3px'
})

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('product-component', require('./components/ProductComponent.vue').default);

// For Toast
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

window.Swal = Swal;
// window.Toast = Toast;

const app = new Vue({
    el: '#app',
});