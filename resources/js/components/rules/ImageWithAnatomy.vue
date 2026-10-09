<template>
  <div class="anatomy-container">
    <rule-image :name="name" :alt="alt" />
    <AnatomyTable>
      <slot></slot>
    </AnatomyTable>
  </div>
</template>

<script>
import RuleImage from './RuleImage.vue';
import AnatomyTable from './AnatomyTable.vue';

/** @class ImageWithAnatomy */
export default {
  name: 'ImageWithAnatomy',
  components: { RuleImage, AnatomyTable },
  props: {
    /** Rules image name, see RuleImage. */
    name: {
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
@import 'element-plus/theme-chalk/src/mixins/mixins.scss';

.anatomy-container {
  display: flex;
  align-items: flex-start;
  flex-direction: column;

  @include res(sm) {
    flex-direction: row;
  }
}
</style>
