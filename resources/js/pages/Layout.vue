<template>
    <el-container class="container">
        <el-header height="auto">
            <NavMenu />
        </el-header>
        <el-main class="main">
            <router-view></router-view>
        </el-main>
        <el-footer>
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
                    {{ newestDateFormatted }}
                    ({{ newestDateDaysAgo ? `${newestDateDaysAgo} days ago.` : 'Less than a day ago.'}})
                </span>
            </p>
        </el-footer>
    </el-container>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import ExternalLink from '../components/common/ExternalLink';
  import NavMenu from '../components/NavMenu';

  /** @class Layout */
  export default {
    name: 'Layout',
    components: { ExternalLink, NavMenu },
    computed: {
      ...mapState('cards', ['newestDate']),
      newestDateFormatted() {
        return this.newestDate ?
          (new Intl.DateTimeFormat('default', {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
          })).format(this.newestDate) :
          null;
      },
      newestDateDaysAgo() {
        const newestDate = this.newestDate;
        if (!newestDate) {
          return;
        }

        return Math.floor(((new Date()) - newestDate.getTime()) / (1000 * 60 * 60 * 24));
      },
    },
    methods: mapActions('cards', ['loadNewestCardDate']),
    created() {
      this.loadNewestCardDate();
    },
  };
</script>

<style lang="scss">
    .main {
        overflow: visible;
    }
</style>
