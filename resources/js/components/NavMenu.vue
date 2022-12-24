<template>
  <v-toolbar style="position: static">
    <template #prepend>
      <a-menu-item route="/" exact>
        <span>{{ $t('nav.welcome') }}</span>
      </a-menu-item>
      <a-menu-item route="/news">
        <span>{{ $t('nav.news') }}</span>
      </a-menu-item>
      <a-menu-item route="/rules">
        <span>{{ $t('nav.rules') }}</span>
      </a-menu-item>
      <a-menu-item route="/cards">
        <span>{{ $t('nav.cardList') }}</span>
      </a-menu-item>
      <a-menu-item v-if="isLocalEnv" route="/deck">
        <span>{{ $t('nav.deck') }}</span>
      </a-menu-item>
    </template>

    <template #append>
      <v-card-text v-if="authDetails">
        Welcome, <strong>{{ authDetails.name }}!</strong>
      </v-card-text>
      <a-menu-item href="/logout" v-if="authDetails">
        <span>Log out</span>
      </a-menu-item>
      <a-menu-item route="/help-translate">
        <span>Help Translate</span>
      </a-menu-item>

      <div style="width: 1rem" />

      <a :href="enHref" class="language-link" :class="{ active: locale !== 'ja' }">
        <FlagImage locale="en" />
      </a>
      <a :href="jaHref" class="language-link" :class="{ active: locale === 'ja' }">
        <FlagImage locale="ja" />
      </a>
    </template>
  </v-toolbar>
</template>

<script>
import FlagImage from './common/FlagImage.vue';
import AMenu from './ui/AMenu.vue';
import AMenuItem from './ui/AMenuItem.vue';

/** @class NavMenu */
export default {
  components: { AMenuItem, AMenu, FlagImage },
  data() {
    return {
      locale: window.locale,
      authDetails: window.vars.auth,
      isLocalEnv: window.vars.environment === 'local',
    };
  },
  computed: {
    getLocaleChangeUrl() {
      let fullPath = this.$route.fullPath;

      return locale => {
        let url = fullPath.indexOf('?') >= 0 ? '&' : '?';
        url += `locale=${locale}`;
        return url;
      };
    },
    enHref() {
      return this.getLocaleChangeUrl('en');
    },
    jaHref() {
      return this.getLocaleChangeUrl('ja');
    },
  },
  methods: {
    logout() {
      window.location.push('/logout');
    },
  },
};
</script>

<style scoped lang="scss">
.language-links {
  float: right;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.language-link {
  margin-left: 0.5em;

  &:not(.active) {
    opacity: 0.25;
  }
}
</style>
