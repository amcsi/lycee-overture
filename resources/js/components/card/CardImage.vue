<template>
    <img alt="card" :src="src" :height="height" :width="width" :style="styles" />
</template>

<script>
import { cardHeightWidthRatio } from '../../../sass/_variables.scss';
import { assembleCloudinaryImageUrl } from '../../utils/image';

/** @class CardImage */
  export default {
    name: 'CardImage',
    props: {
      id: {
        type: String,
        required: true,
      },
      height: {
        type: Number,
        default: false,
      },
      cloudinaryHeight: {
        type: Number,
        default: false,
      },
      styles: {
        type: Object,
        default() {
          return {};
        },
      },
    },
    computed: {
      src() {
        return assembleCloudinaryImageUrl(this.id, this);
      },
      width() {
        // Card width/height ratio.
        return this.height ? this.height * cardHeightWidthRatio : this.height;
      },
    },
  };
</script>

<style scoped lang="scss">
@import 'resources/sass/variables';

$borderHeightRadius: 1.92%;
$borderWidthRadius: $borderHeightRadius * $cardHeightWidthRatio;

img {
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .1);
  border-radius: #{$borderHeightRadius} / #{$borderWidthRadius};
}
</style>
