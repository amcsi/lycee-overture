<template>
  <v-btn :to="route" variant="text" color="medium-emphasis" v-bind="$attrs">
    <slot></slot>
  </v-btn>
</template>

<script>
/** @class AMenuItem */
export default {
  name: 'AMenuItem',
  props: {
    route: String,
  },
};
</script>

<style scoped lang="scss"></style>
