<template>
  <textarea
    v-if="$attrs.type === 'textarea'"
    v-bind="$attrs"
    :value="value"
    @input="$emit('input', $event.target.value)"
  ></textarea>
  <input v-else v-bind="$attrs" :value="value" @input="$emit('input', $event.target.value)" />
</template>

<script>
/** @class AInput */
export default {
  name: 'AInput',
  props: {
    value: String,
  },
};
</script>

<style scoped lang="scss">
input,
textarea {
  border: 1px solid lightgray;
  padding-left: 0.5rem;
}
</style>
