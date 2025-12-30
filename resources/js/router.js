/**
 * Vue Router.
 */

import { defineAsyncComponent } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import Layout from "./pages/Layout.vue";
import NewsPage from "./pages/NewsPage.vue";
import NotFoundPage from "./pages/NotFoundPage.vue";

const CardListPrintPage = defineAsyncComponent(() => import("./pages/CardListPrintPage.vue"));
const CardListPage = defineAsyncComponent(() => import("./pages/CardListPage.vue"));
const DeckPage = defineAsyncComponent(
  () => import(/* webpackChunkName: "deck" */ "./pages/DeckPage.vue"),
);
const DeckCardListPage = defineAsyncComponent(
  () => import(/* webpackChunkName: "deck" */ "./pages/Deck/DeckCardListPage.vue"),
);
const RulesPage = defineAsyncComponent(
  () => import(/* webpackChunkName: "rules" */ "./pages/RulesPage.vue"),
);
const IndexPage = defineAsyncComponent(() => import("./pages/IndexPage.vue"));
const HelpTranslatePage = defineAsyncComponent(() => import("./pages/HelpTranslatePage.vue"));
const DeckToMigrationConverterPage = defineAsyncComponent(
  () => import("./pages/DeckToMigrationConverterPage.vue"),
);

const title = "Lycee Overture TCG Translations";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/cards/print", component: CardListPrintPage },
    {
      path: "/",
      component: Layout,
      children: [
        {
          path: "/",
          component: IndexPage,
          meta: {
            title,
          },
        },
        {
          path: "/news",
          component: NewsPage,
          meta: {
            title: `${title} - News`,
          },
        },
        {
          path: "/rules",
          component: RulesPage,
          meta: {
            title: `${title} - Rules`,
          },
          redirect: "/rules/deck-and-card",
          children: [
            {
              path: "deck-and-card",
              component() {
                return import(
                  /* webpackChunkName: "rules" */ "./components/rules/DeckAndCardRules.vue"
                );
              },
            },
            {
              path: "field",
              component() {
                return import(/* webpackChunkName: "rules" */ "./components/rules/FieldRules.vue");
              },
            },
            {
              path: "cost",
              component() {
                return import(/* webpackChunkName: "rules" */ "./components/rules/CostRules.vue");
              },
            },
            {
              path: "flow",
              component() {
                return import(
                  /* webpackChunkName: "rules" */ "./components/rules/FlowOfGameRules.vue"
                );
              },
            },
            {
              path: "turn",
              component() {
                return import(/* webpackChunkName: "rules" */ "./components/rules/TurnRules.vue");
              },
            },
            {
              path: "battle",
              component() {
                return import(/* webpackChunkName: "rules" */ "./components/rules/BattleRules.vue");
              },
            },
            {
              path: "basic-abilities-and-the-stack",
              component() {
                return import(
                  /* webpackChunkName: "rules" */ "./components/rules/BasicAbilityRules.vue"
                );
              },
            },
          ],
        },
        {
          path: "/help-translate",
          component: HelpTranslatePage,
          meta: {
            title: `${title} - Help Translate`,
          },
        },
        {
          path: "/cards",
          component: CardListPage,
          meta: {
            title: `${title} - Card List`,
          },
        },
        {
          path: "/deck",
          component: DeckPage,
          meta: {
            title: `${title} - Deck`,
          },
          children: [
            {
              path: ":deck",
              title: `${title} - Deck Card List`,
              component: DeckCardListPage,
            },
          ],
        },
        {
          path: "/deck-to-migration-converter",
          component: DeckToMigrationConverterPage,
        },
        { path: "/:pathMatch(.*)*", component: NotFoundPage }, // 404.
      ],
    },
    { path: "/:pathMatch(.*)*", component: NotFoundPage }, // 404.
  ],
});

// https://alligator.io/vuejs/vue-router-modify-head/
// This callback runs before every route change, including on page load.
router.beforeEach((to, from, next) => {
  // This goes through the matched routes from last to first, finding the closest route with a title.
  // eg. if we have /some/deep/nested/route and /some, /deep, and /nested have titles, nested's will be chosen.
  const nearestWithTitle = to.matched
    .slice()
    .reverse()
    .find((r) => r.meta && r.meta.title);

  // Find the nearest route element with meta tags.
  const nearestWithMeta = to.matched
    .slice()
    .reverse()
    .find((r) => r.meta && r.meta.metaTags);

  // If a route with a title was found, set the document (page) title to that value.
  if (nearestWithTitle) document.title = nearestWithTitle.meta.title;

  // Remove any stale meta tags from the document using the key attribute we set below.
  Array.from(document.querySelectorAll("[data-vue-router-controlled]")).map((el) =>
    el.parentNode.removeChild(el),
  );

  // Skip rendering meta tags if there are none.
  if (!nearestWithMeta) return next();

  // Turn the meta tag definitions into actual elements in the head.
  nearestWithMeta.meta.metaTags
    .map((tagDef) => {
      const tag = document.createElement("meta");

      Object.keys(tagDef).forEach((key) => {
        tag.setAttribute(key, tagDef[key]);
      });

      // We use this to track which meta tags we create, so we don't interfere with other ones.
      tag.setAttribute("data-vue-router-controlled", "");

      return tag;
    })
    // Add the meta tags to the document head.
    .forEach((tag) => document.head.appendChild(tag));

  next();
});

export default router;
