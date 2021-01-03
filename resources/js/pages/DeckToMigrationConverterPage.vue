<template>
  <DeckToMigrationConverter :source.sync="source" />
</template>

<script>
import DeckToMigrationConverter from '../components/DeckToMigrationConverter';

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

<style scoped lang="scss">

</style>
