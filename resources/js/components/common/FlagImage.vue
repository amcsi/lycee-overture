<template>
  <img v-if="locale === 'en'" src="../../../images/flags/gb.png" :alt="title" :title="title" />
  <img v-else-if="locale === 'ja'" src="../../../images/flags/jp.png" :alt="title" :title="title" />
  <span v-else />
</template>

<script>
/** @class FlagImage */
export default {
  name: 'FlagImage',
  props: {
    locale: String,
  },
  computed: {
    title() {
      switch (this.locale) {
        case 'en':
          return 'English';
        case 'ja':
          return '日本語';
      }
    },
  },
};
</script>

<style scoped lang="scss"></style>
