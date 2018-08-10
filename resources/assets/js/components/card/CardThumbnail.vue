<template>
    <div>
        <CardImage
            :id="id"
            :height="150"
            :cloudinary-height="150"
            @mouseenter.native="mouseEnter"
            @mouseleave.native="showBiggerImage = false"
        />

        <!-- Preview image stretched out for incremental image loading -->
        <CardImage
            ref="bigImagePreview"
            class="biggerImage"
            v-show="showBiggerImage"
            :id="id"
            :height="300"
            :cloudinary-height="150"
        />

        <CardImage
            ref="bigImage"
            class="biggerImage"
            v-show="showBiggerImage"
            :id="id"
            :height="300"
            :cloudinary-height="300"
        />
    </div>
</template>

<script>
  import Popper from 'popper.js';
  import CardImage from './CardImage';

  /** @class CardThumbnail */
  export default {
    name: 'CardThumbnail',
    data() {
      return {
        showBiggerImage: false,
      };
    },
    props: {
      id: {
        type: String,
        required: true,
      },
    },
    components: { CardImage },
    created() {
      this.poppers = [];
    },
    methods: {
      setupPopper() {
        const reference = this.$el.closest('td');
        const onUpdate = (data) => {
        };
        const options = {
          placement: 'right', positionFixed: true, onUpdate, onCreate: onUpdate,
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
        this.showBiggerImage = true;
        this.$nextTick(() => {
          this.setupPopper();
        });
      },
    },
    destroyed() {
      this.poppers.forEach(popper => popper.destroy());
    },
  };
</script>

<style scoped>
    .biggerImage {
        position: absolute;
        bottom: -312px;
        left: 10px;
    }
</style>
