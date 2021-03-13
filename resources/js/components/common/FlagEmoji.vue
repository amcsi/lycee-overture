<template>
  <span>{{ emoji }}</span>
</template>

<script>
/** @class FlagEmoji */
export default {
  name: 'FlagEmoji',
  props: {
    locale: String,
  },
  computed: {
    emoji() {
      if (this.locale === 'ja') {
        return '🇯🇵';
      }
      if (this.locale === 'en') {
        return '🇬🇧';
      }
    },
  },
};
</script>

<style scoped lang="scss"></style>
