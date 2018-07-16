/**
 * Vue Router.
 */

import VueRouter from 'vue-router';
import CardList from './components/CardList';

const router = new VueRouter({
  mode: 'history', // HTML5 history.
  routes: [
    { path: '/cards', component: CardList },
  ],
});

export default router;
