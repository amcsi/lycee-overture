<template>
  <img v-bind="$attrs" :alt="alt" :src="_src" />
</template>

<script>
/**
 * <img> tag, but proxies it through Cloudinary.
 *
 * @class CloudinaryFetchImg
 **/
export default {
  name: 'CloudinaryFetchImg',
  props: {
    src: String,
    alt: String,
  },
  computed: {
    _src() {
      return `https://res.cloudinary.com/${window.vars.cloudinaryCloudName}/image/fetch/q_auto,f_auto/${this.src}`;
    },
  },
};
</script>

<style scoped lang="scss"></style>
