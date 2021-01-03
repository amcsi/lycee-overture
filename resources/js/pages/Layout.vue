<template>
    <el-container class="container">
        <el-header height="auto">
            <NavMenu />
        </el-header>
        <el-main class="main">
            <router-view></router-view>
        </el-main>
        <el-footer height="auto">
          <el-card>
            <p>Created by
              <ExternalLink href="https://www.szeremi.org/">Attila Szeremi</ExternalLink>
              . Drop me an email at
              <strong>lycee-overture@szeremi.org</strong>, or follow me on
              <ExternalLink href="https://twitter.com/amcsi_san">Twitter</ExternalLink>
              .<br />
              Website source code can be found on
              <ExternalLink
                  href="https://github.com/amcsi/lycee-overture"
              >GitHub
              </ExternalLink>
              .<br />
              Thanks to Yee Cheng Xuan for consulting with me with the automatic translations.
            </p>

            <p v-if="newestDate !== null">
              Newest card(s) last imported:<br />
              <span :style="{ zoom: !newestDate ? 0.5 : 1 }" v-loading="!newestDate">
                        {{ newestDate | formatDate }}
                        ({{ newestDateDaysAgo ? `${newestDateDaysAgo} days ago.` : 'Less than a day ago.' }})
                    </span>
            </p>

            <p v-if="newestSuggestion !== null">
              Newest unapproved translation suggestion by <strong>{{ newestSuggestion.creator.name }}</strong>:<br />
              <span>
                        {{ newestSuggestion.created_at | formatDate }}
                        ({{
                  newestSuggestionDaysAgo ? `${newestSuggestionDaysAgo} days ago.` : 'Less than a day ago.'
                }})
                    </span>
            </p>
          </el-card>

          <div v-if="$route.path === '/'">
            <h3>Latest news</h3>
            <LatestArticles :limit="2" />
          </div>
        </el-footer>
    </el-container>
</template>

<script>
import api from '../api';
import LatestArticles from '../components/article/LatestArticles';
import ExternalLink from '../components/common/ExternalLink';
import NavMenu from '../components/NavMenu';

/** @class Layout */
export default {
  name: 'Layout',
  components: { LatestArticles, ExternalLink, NavMenu },
  data() {
    return {
      footerData: null,
    };
  },
  computed: {
    newestDate() {
      const newestCardLastImported = this.footerData?.newestCardLastImported;

      return newestCardLastImported ? new Date(newestCardLastImported) : null;
    },
    newestSuggestion() {
      return this.footerData?.newestSuggestion;
    },
    newestDateDaysAgo() {
      const newestDate = this.newestDate;
      if (!newestDate) {
        return;
      }

      return Math.floor(((new Date()) - newestDate.getTime()) / (1000 * 60 * 60 * 24));
    },
    newestSuggestionDaysAgo() {
      const newestDate = this.newestSuggestion?.created_at;
      if (!newestDate) {
        return;
      }

      return Math.floor(((new Date()) - new Date(newestDate).getTime()) / (1000 * 60 * 60 * 24));
    },
  },
  async created() {
    this.footerData = (await api.get('/footer-data')).data.data;
  },
};
</script>

<style lang="scss">
    .main {
        overflow: visible;
    }

    footer p:first-child {
        margin-top: 0;
    }
</style>
