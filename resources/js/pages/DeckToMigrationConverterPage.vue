<template>
  <DeckToMigrationConverter v-model:source="source" />
</template>

<script>
import DeckToMigrationConverter from '../components/DeckToMigrationConverter.vue';

/** @class DeckToMigrationConverterPage */
export default {
  name: 'DeckToMigrationConverterPage',
  components: { DeckToMigrationConverter },
  data() {
    return {
      source: '',
    };
  },
};
</script>

<style scoped lang="scss"></style>
