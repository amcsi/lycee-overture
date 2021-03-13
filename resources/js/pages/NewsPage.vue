<template>
  <div>
    <h1>News</h1>
    <LatestArticles />
  </div>
</template>

<script>
import LatestArticles from '../components/article/LatestArticles';
import CardFilters from '../components/card/CardFilters';
import CardList from '../components/CardList';
import CardListContainer from '../components/container/CardListContainer';

/** @class CardListPage */
export default {
  name: 'CardListPage',
  components: { LatestArticles, CardList, CardFilters, CardListContainer },
};
</script>

<style scoped></style>
