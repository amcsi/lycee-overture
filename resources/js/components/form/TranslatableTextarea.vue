<template>
  <el-form-item>
    <slot name="label"
      ><div class="label">{{ _label }}</div></slot
    >

    <a-input
      type="textarea"
      :value="value"
      @input="$emit('input', $event)"
      :placeholder="placeholder"
    ></a-input>
  </el-form-item>
</template>

<script>
import AInput from '../ui/AInput.vue';

/** @class TranslatableTextarea */
export default {
  name: 'TranslatableTextarea',
  components: { AInput },
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

<style scoped lang="scss">
.label {
  width: 10rem;
}

textarea {
  width: 30rem;
}
</style>
