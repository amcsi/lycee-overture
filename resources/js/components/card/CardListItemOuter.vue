<template>
  <LazyComponent class="card-list-item-outer" style="min-height: 10rem">
    <CardListItem :card="card" />
  </LazyComponent>
</template>

<script>
import LazyComponent from '../common/LazyComponent.vue';
import CardListItem from './CardListItem.vue';

export default {
  name: 'CardListItemOuter',
  components: {
    CardListItem,
    LazyComponent,
  },
  props: {
    card: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style scoped lang="scss">
.el-card {
  // For the thumbnails.
  overflow: visible;
}
</style>
