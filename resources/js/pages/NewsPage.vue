<template>
  <div>
    <h1>News</h1>
    <LatestArticles />
  </div>
</template>

<script>
import LatestArticles from '../components/article/LatestArticles.vue';
import CardFilters from '../components/card/CardFilters.vue';
import CardList from '../components/CardList.vue';
import CardListContainer from '../components/container/CardListContainer.vue';

/** @class CardListPage */
export default {
  name: 'CardListPage',
  components: { LatestArticles, CardList, CardFilters, CardListContainer },
};
</script>

<style scoped></style>
