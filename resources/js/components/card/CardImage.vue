<template>
    <img :src="src" :height="height" :width="width" />
</template>

<script>
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
    },
    computed: {
      src() {
        return assembleCloudinaryImageUrl(this.id, this);
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
