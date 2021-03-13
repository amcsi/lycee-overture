<template>
  <table>
    <slot></slot>
  </table>
</template>

<script>
/**
 * Container for the anatomy for a type of card.
 *
 * @class AnatomyTable
 **/
export default {
  name: 'AnatomyTable',
};
</script>

<style scoped>
table {
  border-collapse: collapse;
}
</style>
