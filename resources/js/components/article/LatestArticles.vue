<template>
    <section v-loading="articles === null">
        <h3>Latest news</h3>

        <article v-for="{ title, html, created_at } in articles">
            <div>
                <h4>{{ title }}</h4> - <time :datetime="created_at">{{ created_at | formatDate }}</time>
            </div>

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
    h4 {
        display: inline;
    }

    time {
        font-size: .9em;
    }
</style>
