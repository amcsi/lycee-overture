<template>
  <section v-loading="articles === null">
    <article v-for="{ title, content, date_gmt } in articles">
      <el-card style="margin-bottom: 1rem">
        <div>
          <h4>{{ title.rendered }}</h4>
          -
          <time :datetime="date_gmt + 'Z'">{{ (date_gmt + 'Z') | formatDate }}</time>
        </div>

        <div v-html="content.rendered"></div>
      </el-card>
    </article>
    <div v-if="articles && !articles.length">No articles.</div>
  </section>
</template>

<script>
import { mapActions, mapState } from 'vuex';

/** @class LatestArticles */
export default {
  name: 'LatestArticles',
  props: {
    limit: Number,
  },
  computed: {
    ...mapState('articles', ['articles']),
  },
  methods: {
    ...mapActions('articles', ['loadArticles']),
  },
  created() {
    this.loadArticles(this.limit);
  },
};
</script>

<style scoped>
h4 {
  display: inline;
}

time {
  font-size: 0.9em;
}
</style>
