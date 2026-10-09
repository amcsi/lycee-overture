<template>
  <img v-bind="$attrs" :alt="alt" :src="src" />
</template>

<script>
import { imageUrl } from '../../utils/image';

/**
 * <img> of a rules image mirrored from lycee-tcg.com into resources/images/rules.
 *
 * @class RuleImage
 **/
export default {
  name: 'RuleImage',
  props: {
    /** File name without extension, e.g. "index_2_img1". */
    name: String,
    alt: String,
  },
  computed: {
    src() {
      return imageUrl(`rules/${this.name}`);
    },
  },
};
</script>

<style scoped lang="scss"></style>
