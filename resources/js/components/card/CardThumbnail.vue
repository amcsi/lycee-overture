<template>
    <div class="card-thumbnail-container">
        <!-- Only open the image url in a new page if the thumbnail had already been clicked on before mouseleave -->
        <a target="_blank" rel="nofollow" :href="largerImage ? imageUrl : null">
            <CardImage
                :id="id"
                :height="150"
                :cloudinary-height="150"
                @mouseenter.native="mouseEnter"
                @mouseleave.native="mouseLeave"
                @click.native="click"
                style="cursor: pointer;"
            />
        </a>

        <!-- Preview image stretched out for incremental image loading -->
        <CardImage
            ref="bigImagePreview"
            class="biggerImage"
            v-show="revealImageOverlay"
            :id="id"
            :height="height"
            :cloudinary-height="150"
        />

        <CardImage
            ref="bigImage"
            class="biggerImage"
            v-show="revealImageOverlay"
            :id="id"
            :height="height"
            :cloudinary-height="height"
        />
    </div>
</template>

<script>
  import Popper from 'popper.js';
  import { assembleCloudinaryImageUrl } from '../../utils/image';
  import CardImage from './CardImage';

  /** @class CardThumbnail */
  export default {
    name: 'CardThumbnail',
    data() {
      return {
        largerImage: false,
        revealImageOverlay: false,
      };
    },
    props: {
      id: {
        type: String,
        required: true,
      },
    },
    components: { CardImage },
    computed: {
      height() {
        return this.largerImage ? 520 : 300;
      },
      imageUrl() {
        return assembleCloudinaryImageUrl(this.id, { cloudinaryHeight: this.height });
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
      click(event) {
        if (!this.largerImage) {
          // Because of Vue 2.6+ macrotasks, we need to prevent default, because a click handler would appear on the
          // parent before event bubbling begins that we _don't_ want triggered with this click.
          event.preventDefault();
        }
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
</style>
