<template>
  <span>
    <label>
      <input
        type="checkbox"
        :value="value"
        @input="
          $emit('input', $event.target.checked ? this.trueLabel ?? 'on' : this.falseValue ?? '')
        "
        v-bind="$attrs"
      />
      {{ label }}
    </label>
  </span>
</template>

<script>
/** @class ACheckbox */
export default {
  name: 'ACheckbox',
  props: {
    label: String,
    value: String,
    'true-label': String,
    'false-label': String,
  },
};
</script>

<style scoped lang="scss"></style>
