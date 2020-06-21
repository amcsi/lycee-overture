/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import {
  Card,
  Checkbox,
  Col,
  Container,
  Footer,
  Form,
  FormItem,
  Header,
  Input,
  Loading,
  Main,
  Menu,
  MenuItem,
  Option,
  Pagination,
  Row,
  Select,
  Switch,
  Table,
  TableColumn,
} from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import Rollbar from 'rollbar';
import VueRouter from 'vue-router';
import App from './App';
import i18n from './i18n';
import router from './router';
import store from './store/index';

require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.component(Card.name, Card);
Vue.component(Checkbox.name, Checkbox);
Vue.component(Container.name, Container);
Vue.component(Col.name, Col);
Vue.component(Footer.name, Footer);
Vue.component(Form.name, Form);
Vue.component(FormItem.name, FormItem);
Vue.component(Header.name, Header);
Vue.component(Input.name, Input);
Vue.component(Main.name, Main);
Vue.component(Menu.name, Menu);
Vue.component(MenuItem.name, MenuItem);
Vue.component(Option.name, Option);
Vue.component(Pagination.name, Pagination);
Vue.component(Row.name, Row);
Vue.component(Select.name, Select);
Vue.component(Switch.name, Switch);
Vue.component(Table.name, Table);
Vue.component(TableColumn.name, TableColumn);
Vue.use(Loading.directive);

//noinspection JSUnusedGlobalSymbols

const rollbar = Rollbar.init({
  accessToken: window.vars.rollbarToken,
  captureUncaught: true,
  captureUnhandledRejections: true,
  enabled: true,
  source_map_enabled: true,
  environment: window.vars.environment,
  payload: {
    client: {
      javascript: {
        code_version: window.vars.gitSha1,
      }
    }
  }
});
Vue.prototype.$rollbar = rollbar;
Vue.config.errorHandler = function(err) {
  console.error(err);
  rollbar.error(err);
};

const dateFormatter = new Intl.DateTimeFormat(void 0, {
  year: 'numeric',
  month: 'numeric',
  day: 'numeric',
  hour: 'numeric',
  minute: 'numeric',
});

Vue.filter('formatDate', function(value) {
  if (typeof value === 'string') {
    value = new Date(value);
  }
  return value ? dateFormatter.format(value) : '';
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
new Vue({
  router,
  store,
  i18n,
  render(h) {
    return h(App);
  },
  el: '#app',
});
