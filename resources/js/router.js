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
const RulesPage = loadingizeAsyncComponent(() => import('./pages/RulesPage'));
const IndexPage = loadingizeAsyncComponent(() => import ('./pages/IndexPage'));

const title = 'Lycee Overture TCG Translations';

const router = new VueRouter({
  mode: 'history', // HTML5 history.
  routes: [
    { path: '/cards/print', component: CardListPrintPage },
    {
      path: '', component: Layout, children: [
        {
          path: '/', component: IndexPage, meta: {
            title,
          },
        },
        {
          path: '/rules', component: RulesPage, meta: {
            title: `${title} - Rules`,
          },
        },
        {
          path: '/cards', component: CardListPage, meta: {
            title: `${title} - Card List`,
          },
        },
      ],
    },
  ],
});

// https://alligator.io/vuejs/vue-router-modify-head/
// This callback runs before every route change, including on page load.
router.beforeEach((to, from, next) => {
  // This goes through the matched routes from last to first, finding the closest route with a title.
  // eg. if we have /some/deep/nested/route and /some, /deep, and /nested have titles, nested's will be chosen.
  const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);

  // Find the nearest route element with meta tags.
  const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

  // If a route with a title was found, set the document (page) title to that value.
  if (nearestWithTitle) document.title = nearestWithTitle.meta.title;

  // Remove any stale meta tags from the document using the key attribute we set below.
  Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(
    el));

  // Skip rendering meta tags if there are none.
  if (!nearestWithMeta) return next();

  // Turn the meta tag definitions into actual elements in the head.
  nearestWithMeta.meta.metaTags.map(tagDef => {
    const tag = document.createElement('meta');

    Object.keys(tagDef).forEach(key => {
      tag.setAttribute(key, tagDef[key]);
    });

    // We use this to track which meta tags we create, so we don't interfere with other ones.
    tag.setAttribute('data-vue-router-controlled', '');

    return tag;
  })
  // Add the meta tags to the document head.
    .forEach(tag => document.head.appendChild(tag));

  next();
});

export default router;
