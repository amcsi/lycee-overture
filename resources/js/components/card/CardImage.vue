<template>
    <img :src="src" :height="height" :width="width" />
</template>

<script>
  import getCardImageUrl from '../../utils/getCardImageUrl';

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
    },
    computed: {
      src() {
        return getCardImageUrl(this.id, this.cloudinaryHeight);
      },
      width() {
        // Card width/height ratio.
        return this.height ? this.height * 0.71538461538 : this.height;
      },
    },
  };
</script>

<style scoped>

</style>
