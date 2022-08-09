<template>
  <div class="anatomy-container">
    <cloudinary-fetch-img :src="src" :alt="alt" />
    <AnatomyTable>
      <slot></slot>
    </AnatomyTable>
  </div>
</template>

<script>
import CloudinaryFetchImg from '../common/CloudinaryFetchImg.vue';
import AnatomyTable from './AnatomyTable.vue';

/** @class ImageWithAnatomy */
export default {
  name: 'ImageWithAnatomy',
  components: { CloudinaryFetchImg, AnatomyTable },
  props: {
    src: {
      type: String,
      required: true,
    },
    alt: {
      type: String,
      required: true,
    },
  },
};
</script>

<style scoped lang="scss">
@import 'element-ui/packages/theme-chalk/src/mixins/mixins.scss';

.anatomy-container {
  display: flex;
  align-items: flex-start;
  flex-direction: column;

  @include res(sm) {
    flex-direction: row;
  }
}
</style>
