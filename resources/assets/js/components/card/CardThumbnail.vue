<template>
    <div>
        <CardImage
            :id="id"
            :height="150"
            :cloudinary-height="150"
            @mouseenter.native="showBiggerImage = true"
            @mouseleave.native="showBiggerImage = false"
            @mousemove.native="moveCardImageOverlay"
        />

        <!-- Preview image stretched out for incremental image loading -->
        <CardImage class="biggerImage" v-if="showBiggerImage" :id="id" :height="520" :cloudinary-height="150" />

        <CardImage class="biggerImage" v-if="showBiggerImage" :id="id" :height="520" />
    </div>
</template>

<script>
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
    methods: {
      moveCardImageOverlay() {

      },
    },
  };
</script>

<style scoped>
    .biggerImage {
        position: absolute;
        bottom: -532px;
        left: 10px;
    }
</style>
