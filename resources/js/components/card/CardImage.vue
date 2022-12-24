<template>
  <div class="container" :style="computedContainerStyle" v-on="$listeners">
    <img
      v-if="fallbackToCardback"
      alt="fallback"
      :src="cardbackSrc"
      :height="height"
      :width="width"
      :style="styles"
    />
    <img alt="card" :key="key" :src="src" :height="height" :width="width" :style="styles" />
  </div>
</template>

<script>
import variables from '../../../sass/_variables.module.scss';
import { assembleCloudinaryImageCardUrl, assembleCloudinaryImageUrl } from '../../utils/image';

const { cardHeightWidthRatio } = variables;

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
    fallbackToCardback: {
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
      return assembleCloudinaryImageCardUrl(this.id + (this.variant ?? ''), this);
    },
    width() {
      // Card width/height ratio.
      return this.height ? this.height * cardHeightWidthRatio : this.height;
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
    cardbackSrc() {
      return assembleCloudinaryImageUrl('cardback');
    },
  },
};
</script>

<style scoped lang="scss">
@import '../../../sass/variables.module';

$borderHeightRadius: 1.92%;
$borderWidthRadius: $borderHeightRadius * $cardHeightWidthRatio;

img {
  position: absolute;
  top: 0;
  left: 0;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  border-radius: #{$borderHeightRadius} / #{$borderWidthRadius};
}

.container {
  position: relative;
}
</style>
