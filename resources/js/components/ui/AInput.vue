<template>
  <el-input v-bind="$attrs" :model-value="modelValue" @input="$emit('update:modelValue', $event)" />
</template>

<script>
/** @class AInput */
export default {
  name: 'AInput',
  props: {
    modelValue: String,
  },
  emits: ['update:modelValue'],
};
</script>

<style scoped lang="scss">
input,
textarea {
  border: 1px solid lightgray;
  padding-left: 0.5rem;
}
</style>
