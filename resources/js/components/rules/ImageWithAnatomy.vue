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

<style scoped>
    .anatomy-container {
        display: flex;
        align-items: flex-start;
    }
</style>
