/**
 * Vue Router.
 */

import VueRouter from 'vue-router';

const asyncComponentize = componentPromise => () => ({
  // The component to load (should be a Promise)
  component: componentPromise,
  // A component to use while the async component is loading
  loading: {
    render(h) {
      return h('div', {directives: [{ loading: true }]});
    },
  },
  // A component to use if the load fails
  error: null,
  // Delay before showing the loading component. Default: 200ms.
  delay: 200,
  // The error component will be displayed if a timeout is
  // provided and exceeded. Default: Infinity.
  timeout: Infinity,
});

const CardList = asyncComponentize(import('./components/CardList'));
const IndexPage = asyncComponentize(import ('./pages/IndexPage'));

const router = new VueRouter({
  mode: 'history', // HTML5 history.
  routes: [
    { path: '/', component: IndexPage },
    { path: '/cards', component: CardList },
  ],
});

export default router;
