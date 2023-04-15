<template>
  <el-menu router mode="horizontal" :default-active="$route.path" :ellipsis="false">
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

    <li class="flex-grow" />

    <li class="el-menu-item" v-if="authDetails">
      <span>
        Welcome, <strong>{{ authDetails.name }}!</strong>
      </span>
    </li>
    <el-menu-item classindex="/help-translate" route="/help-translate">
      <a href="/help-translate" @click.prevent>Help Translate</a>
    </el-menu-item>
    <a class="el-menu-item" v-if="authDetails" href="/logout"> Log out </a>

    <div class="language-links">
      <a :href="enHref" class="language-link" :class="{ active: activeLocale === 'en' }">
        <FlagImage locale="en" />
      </a>
      <a :href="huHref" class="language-link" :class="{ active: activeLocale === 'hu' }">
        <FlagImage locale="hu" />
      </a>
      <a :href="jaHref" class="language-link" :class="{ active: activeLocale === 'ja' }">
        <FlagImage locale="ja" />
      </a>
    </div>
  </el-menu>
</template>

<script>
import FlagImage from './common/FlagImage.vue';

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
    huHref() {
      return getLocaleChangeUrl(this, 'hu');
    },
    jaHref() {
      return getLocaleChangeUrl(this, 'ja');
    },
    activeLocale() {
      return this.locale ?? 'en';
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

.flex-grow {
  flex-grow: 1;
}

.float-right {
  float: right;
}
</style>
