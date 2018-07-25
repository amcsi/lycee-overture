<template>
    <img :src="src" :height="height" :width="width" />
</template>

<script>
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
        default: 150,
      },
    },
    computed: {
      src() {
        return `https://res.cloudinary.com/${window.vars.cloudinaryCloudName}/image/upload/h_${this.height}/cards/${this.id}.jpg`;
      },
      width() {
        // Card width/height ratio.
        return this.height * 0.71538461538;
      },
    },
  };
</script>

<style scoped>

</style>
