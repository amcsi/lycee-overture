/**
 * Vue Router.
 */

import VueRouter from 'vue-router';
import CardList from './components/CardList';
import IndexPage from './pages/IndexPage';

const router = new VueRouter({
  mode: 'history', // HTML5 history.
  routes: [
    { path: '/', component: IndexPage },
    { path: '/cards', component: CardList },
  ],
});

export default router;
