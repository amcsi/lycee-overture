/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import 'element-ui/lib/theme-chalk/index.css';
import VueRouter from 'vue-router';
import App from './App';
import router from './router';
import store from './store';
import {
  Container,
  Header,
  Main,
  Table,
  TableColumn,
  Menu,
  MenuItem,
  Pagination,
  Footer,
  Loading,
} from 'element-ui';

Vue.use(VueRouter);
Vue.component(Container.name, Container);
Vue.component(Header.name, Header);
Vue.component(Main.name, Main);
Vue.component(Table.name, Table);
Vue.component(TableColumn.name, TableColumn);
Vue.component(Menu.name, Menu);
Vue.component(MenuItem.name, MenuItem);
Vue.component(Pagination.name, Pagination);
Vue.component(Footer.name, Footer);
Vue.use(Loading.directive);

//noinspection JSUnusedGlobalSymbols

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
new Vue({
  router,
  store,
  render(h) {
    return h(App);
  },
  el: '#app',
});
