<template>
  <tr>
    <td class="number-and-name-column">
      <div class="number-and-name-container">
        <rule-image :name="numberImageName" :alt="number" />

        <div class="number-and-name--name">
          {{ name }}
        </div>
      </div>
    </td>
    <td v-if="!this.onlyName">
      <slot>(description goes here)</slot>
    </td>
  </tr>
</template>

<script>
import RuleImage from './RuleImage.vue';

/** @class AnatomyRow */
export default {
  name: 'AnatomyRow',
  components: { RuleImage },
  props: {
    number: {
      type: [Number, String],
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    onlyName: Boolean,
  },
  computed: {
    numberImageName() {
      return this.onlyName
        ? // Blue.
          `index_4_number${this.number}`
        : // Red.
          `index_2_number${this.number}`;
    },
  },
};
</script>

<style scoped lang="scss">
.number-and-name-container {
  display: flex;
  align-items: flex-start;

  img {
    margin-right: 0.5em;
    vertical-align: top;
  }
}

.number-and-name--name {
  padding-top: 0.2em;
}

.number-and-name-column {
  font-weight: bold;
}

td {
  border: 1px solid gray;
  padding: 0.3em;

  line-height: 1.5;
}
</style>
