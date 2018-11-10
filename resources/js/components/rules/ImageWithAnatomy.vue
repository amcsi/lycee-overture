<template>
    <div class="anatomy-container">
        <img :src="src" :alt="alt" />
        <AnatomyTable>
            <slot></slot>
        </AnatomyTable>
    </div>
</template>

<script>
  import AnatomyTable from './AnatomyTable';

  /** @class ImageWithAnatomy */
  export default {
    name: 'ImageWithAnatomy',
    components: { AnatomyTable },
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
    @import '~element-ui/packages/theme-chalk/src/mixins/mixins.scss';

    .anatomy-container {
        display: flex;
        align-items: flex-start;
        flex-direction: column;

        @include res(sm) {
            flex-direction: row;
        }
    }
</style>
