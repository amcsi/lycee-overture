<template>
  <div>
    <h1>News</h1>
    <LatestArticles />
  </div>
</template>

<script>
import LatestArticles from '../components/article/LatestArticles.vue';

/** @class CardListPage */
export default {
  name: 'CardListPage',
  components: { LatestArticles },
};
</script>

<style scoped></style>
