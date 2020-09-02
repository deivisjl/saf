/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

window.$ = window.jQuery = require('jquery');
window.$.fn.DataTable = require( 'datatables.net' );
window.$.fn.DataTable = require( 'datatables.net-bs4' );

window.Swal = require('sweetalert2');
window.Toastr = require('toastr');

require('./bootstrap');
require('./utils');

window.abs_path = '';

import VeeValidate from 'vee-validate';

const VueValidationEs = require('vee-validate/dist/locale/es');

const config = {
  locale: 'es',
  validity: true,
  dictionary: {
    es: VueValidationEs
  },
  fieldsBagName: 'campos',
  errorBagName: 'errors', // change if property conflicts
};

Vue.use(VeeValidate, config);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('error-form', require('./components/shared/ErrorComponent').default);

/* Componente permisos */
Vue.component('nuevo-permiso-component', require('./components/accesos/permisos/NuevoPermisoComponent.vue').default);
Vue.component('editar-permiso-component', require('./components/accesos/permisos/EditarPermisoComponent.vue').default);

/* Componentes rol */
Vue.component('nuevo-rol-component', require('./components/accesos/roles/NuevoRolComponent.vue').default);
Vue.component('editar-rol-component', require('./components/accesos/roles/EditarRolComponent.vue').default);

/* Componente usuario rol */
Vue.component('usuario-rol-component', require('./components/accesos/usuario-rol/UsuarioRolComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
