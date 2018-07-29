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
        default: false,
      },
      cloudinaryHeight: {
        type: Number,
        default: false,
      },
    },
    computed: {
      src() {
        const modifiers = [];
        if (this.cloudinaryHeight) {
          modifiers.push(`h_${this.cloudinaryHeight}`);
        }
        return `https://res.cloudinary.com/${window.vars.cloudinaryCloudName}/image/upload/${modifiers.join(',')}/cards/${this.id}.jpg`;
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
