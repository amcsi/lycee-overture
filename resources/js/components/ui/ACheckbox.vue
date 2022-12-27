<template>
  <span>
    <label>
      <el-checkbox
        :value="value"
        @input="
          $emit(
            'update:modelValue',
            $event.target.checked ? this.trueLabel ?? 'on' : this.falseValue ?? ''
          )
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
    modelValue: String,
    'true-label': String,
    'false-label': String,
  },
  emits: ['update:modelValue'],
};
</script>

<style scoped lang="scss"></style>
