/**
 * Vue Router.
 */

import VueRouter from 'vue-router';
import Layout from './pages/Layout';

/**
 * Loading wrapper for code-split async components in the route.
 */
const loadingizeAsyncComponent = asyncComponent => () => ({
  // The component to load (should be a Promise)
  component: asyncComponent(),
  // A component to use while the async component is loading
  loading: {
    render(h) {
      return h('div', { directives: [{ loading: true }] });
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

const CardListPrintPage = loadingizeAsyncComponent(() => import ('./pages/CardListPrintPage'));
const CardListPage = loadingizeAsyncComponent(() => import('./pages/CardListPage'));
const IndexPage = loadingizeAsyncComponent(() => import ('./pages/IndexPage'));

const router = new VueRouter({
  mode: 'history', // HTML5 history.
  routes: [
    { path: '/cards/print', component: CardListPrintPage },
    {
      path: '', component: Layout, children: [
        { path: '/', component: IndexPage },
        { path: '/cards', component: CardListPage },
      ],
    },
  ],
});

export default router;
