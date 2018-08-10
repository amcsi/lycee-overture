/**
 * Vue Router.
 */

import VueRouter from 'vue-router';
const CardList = () => import('./components/CardList');
const IndexPage = () => import('./pages/IndexPage');

const router = new VueRouter({
  mode: 'history', // HTML5 history.
  routes: [
    { path: '/', component: IndexPage },
    { path: '/cards', component: CardList },
  ],
});

export default router;
