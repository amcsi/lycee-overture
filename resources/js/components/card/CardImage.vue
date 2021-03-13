<template>
  <div :style="computedContainerStyle">
    <img alt="card" :key="key" :src="src" :height="height" :width="width" :style="styles" />
  </div>
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
    noPointerEvents: {
      type: Boolean,
    },
    styles: {
      type: Object,
      default() {
        return {};
      },
    },
    variant: {
      type: String,
    },
  },
  computed: {
    src() {
      return assembleCloudinaryImageUrl(this.id + (this.variant ?? ''), this);
    },
    width() {
      // Card width/height ratio.
      const width = this.height ? this.height * cardHeightWidthRatio : this.height;
      console.info({ width });
      return width;
    },
    computedContainerStyle() {
      const style = { width: `${this.width}px`, height: `${this.height}px` };
      if (this.noPointerEvents) {
        style['pointer-events'] = 'none';
      }
      return style;
    },
    key() {
      // The key ensures that the previous image wouldn't be shown while the newer variant is loading.
      return this.id + this.variant;
    },
  },
};
</script>

<style scoped lang="scss">
@import 'resources/sass/variables';

$borderHeightRadius: 1.92%;
$borderWidthRadius: $borderHeightRadius * $cardHeightWidthRatio;

img {
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  border-radius: #{$borderHeightRadius} / #{$borderWidthRadius};
}
</style>
