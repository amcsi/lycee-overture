<template>
  <div class="card-thumbnail-container">
    <!-- Only open the image url in a new page if the thumbnail had already been clicked on before mouseleave -->
    <a target="_blank" rel="nofollow" :href="largerImage ? imageUrl : null" @click="click">
      <CardImage
        :id="id"
        :variant="variant"
        :height="150"
        :cloudinary-height="150"
        @mouseenter.native="mouseEnter"
        @mouseleave.native="mouseLeave"
        style="cursor: pointer"
      />
    </a>

    <!-- Preview image stretched out for incremental image loading -->
    <CardImage
      ref="bigImagePreview"
      class="biggerImage"
      v-show="revealImageOverlay"
      :id="id"
      :variant="variant"
      :height="height"
      :cloudinary-height="150"
      :styles="{ 'pointer-events': 'none' }"
    />

    <CardImage
      ref="bigImage"
      class="biggerImage"
      v-show="revealImageOverlay"
      :id="id"
      :variant="variant"
      :height="height"
      :cloudinary-height="height"
      :styles="{ 'pointer-events': 'none' }"
    />

    <div class="variants" v-if="variants.length > 1">
      <span
        v-for="{ variant, rarity } of variants"
        class="variant"
        :class="{ notSelected: variant !== savedCardVariant }"
        @mouseenter="selectVariant(variant)"
        @mouseleave="unselectVariant"
        @click="selectVariant(variant, true)"
      >
        {{ rarity }}
      </span>
    </div>
  </div>
</template>

<script>
import Popper from 'popper.js';
import { saveCardVariant, savedCardVariants } from '../../utils/cardVariant';
import { assembleCloudinaryImageUrl } from '../../utils/image';
import CardImage from './CardImage';

/** @class CardThumbnail */
export default {
  name: 'CardThumbnail',
  data() {
    return {
      largerImage: false,
      revealImageOverlay: false,
      hoveringVariant: null,
    };
  },
  props: {
    id: {
      type: String,
      required: true,
    },
    variants: {
      type: Array,
      required: true,
    },
  },
  components: { CardImage },
  computed: {
    height() {
      return this.largerImage ? 520 : 300;
    },
    imageUrl() {
      return assembleCloudinaryImageUrl(this.id + this.variantObj.variant, {
        cloudinaryHeight: this.height,
      });
    },
    savedCardVariant() {
      return savedCardVariants.preferences[this.id] ?? '';
    },
    savedCardVariants() {
      return savedCardVariants;
    },
    variantsByVariantString() {
      const ret = {};
      for (const variantObj of this.variants) {
        ret[variantObj.variant] = variantObj;
      }
      return ret;
    },
    variantObj() {
      const variantObj = this.variantsByVariantString[
        this.hoveringVariant ?? this.savedCardVariant ?? ''
      ];
      if (variantObj) {
        return variantObj;
      }
      return this.variants[0];
    },
    variant() {
      return this.variantObj.variant;
    },
  },
  methods: {
    setupPopper() {
      const reference = this.$el;
      const options = {
        placement: 'left',
        modifiers: {
          flip: {
            behavior: ['left', 'right', 'bottom', 'top'],
          },
          preventOverflow: {
            boundariesElement: 'viewport',
          },
        },
      };
      if (!this.poppers) {
        this.poppers = [];
        this.poppers.push(new Popper(reference, this.$refs.bigImagePreview.$el, options));
        this.poppers.push(new Popper(reference, this.$refs.bigImage.$el, options));
      } else {
        this.poppers.forEach(popper => {
          popper.scheduleUpdate();
        });
      }
    },
    mouseEnter() {
      this.revealImageOverlay = true;
      this.$nextTick(() => {
        this.setupPopper();
      });
    },
    mouseLeave() {
      this.revealImageOverlay = false;
      this.largerImage = false;
    },
    selectVariant(variant, select) {
      this.hoveringVariant = variant;
      if (select) {
        saveCardVariant(this.id, variant);
      }
      this.mouseEnter();
    },
    unselectVariant() {
      this.hoveringVariant = null;
      this.mouseLeave();
    },
    click(event) {
      event.stopPropagation();
      if (this.largerImage) {
        // The tag should be an <a> with an href; just go to that link.
        return;
      }
      event.preventDefault();

      this.largerImage = true;
      this.$nextTick(() => {
        this.setupPopper();
      });
    },
  },
  destroyed() {
    if (this.poppers) {
      this.poppers.forEach(popper => popper.destroy());
    }
  },
};
</script>

<style scoped>
.card-thumbnail-container {
  position: relative;
}

.biggerImage {
  position: absolute;
  bottom: -312px;
  left: 10px;
  z-index: 10;
}

.variants {
  margin-top: 0.25rem;
  display: flex;
  gap: 0.5rem;
}

/*noinspection CssUnusedSymbol*/
.notSelected {
  font-weight: bold;
  cursor: pointer;
}
</style>
