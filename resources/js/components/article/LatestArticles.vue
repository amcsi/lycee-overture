<template>
    <section v-loading="articles === null">
        <h2>Latest news</h2>

        <article v-for="{ title, html, created_at } in articles">
            <h3>{{ title }} - {{ created_at | formatDate }}</h3>

            <div v-html="html">

            </div>
        </article>
        <div v-if="articles && !articles.length">No articles.</div>
    </section>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  /** @class LatestArticles */
  export default {
    name: 'LatestArticles',
    computed: {
      ...mapState('articles', ['articles']),
    },
    methods: {
      ...mapActions('articles', ['loadArticles']),
    },
    created() {
      this.loadArticles();
    },
  };
</script>

<style scoped>

</style>
