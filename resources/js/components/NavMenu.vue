<template>
  <el-menu router mode="horizontal" :default-active="$route.path">
    <el-menu-item index="/" route="/">
      <a href="/" @click.prevent>{{ $t('nav.welcome') }}</a>
    </el-menu-item>
    <el-menu-item index="/news" route="/news">
      <a href="/news" @click.prevent>{{ $t('nav.news') }}</a>
    </el-menu-item>
    <el-menu-item index="/rules" route="/rules">
      <a href="/rules" @click.prevent>{{ $t('nav.rules') }}</a>
    </el-menu-item>
    <el-menu-item index="/cards" route="/cards">
      <a href="/cards" @click.prevent>{{ $t('nav.cardList') }}</a>
    </el-menu-item>
    <el-menu-item v-if="isLocalEnv" index="/deck">
      <router-link to="/deck">{{ $t('nav.deck') }}</router-link>
    </el-menu-item>

    <li class="language-links">
      <a :href="enHref" class="language-link" :class="{ active: locale !== 'ja' }">
        <FlagImage locale="en" />
      </a>
      <a :href="jaHref" class="language-link" :class="{ active: locale === 'ja' }">
        <FlagImage locale="ja" />
      </a>
    </li>

    <el-menu-item style="float: right" index="/help-translate" route="/help-translate">
      <a href="/help-translate" @click.prevent>Help Translate</a>
    </el-menu-item>
    <a class="el-menu-item" v-if="authDetails" style="float: right" href="/logout"> Log out </a>
    <li class="el-menu-item" v-if="authDetails" style="float: right">
      Welcome, <strong>{{ authDetails.name }}!</strong>
    </li>
  </el-menu>
</template>

<script>
import FlagImage from './common/FlagImage';

function getLocaleChangeUrl(vueComponent, locale) {
  let url = vueComponent.$route.fullPath;
  url += url.indexOf('?') >= 0 ? '&' : '?';
  url += `locale=${locale}`;
  return url;
}

/** @class NavMenu */
export default {
  components: { FlagImage },
  data() {
    return {
      locale: window.locale,
      authDetails: window.vars.auth,
      isLocalEnv: window.vars.environment === 'local',
    };
  },
  computed: {
    enHref() {
      return getLocaleChangeUrl(this, 'en');
    },
    jaHref() {
      return getLocaleChangeUrl(this, 'ja');
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
