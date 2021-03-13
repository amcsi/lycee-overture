<template>
  <el-form-item>
    <slot name="label">{{ _label }}</slot>

    <el-input
      type="textarea"
      :value="value"
      @input="$emit('input', $event)"
      :placeholder="placeholder"
    ></el-input>
  </el-form-item>
</template>

<script>
/** @class TranslatableTextarea */
export default {
  name: 'TranslatableTextarea',
  props: {
    label: String,
    translationDraft: Object,
    path: [String, Array],
    placeholder: String,
    value: String,
    dirty: Boolean,
  },
  computed: {
    _label() {
      let ret = this.label;
      if (this.dirty) {
        ret += ' ✍️';
      }
      return ret;
    },
  },
};
</script>

<style scoped lang="scss"></style>
