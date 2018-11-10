<template>
    <tr>
        <td class="number-and-name-column">
            <div class="number-and-name-container">
                <img
                    :src="numberImageUrl"
                    :alt="number"
                />

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
  /** @class AnatomyRow */
  export default {
    name: 'AnatomyRow',
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
      numberImageUrl() {
        return this.onlyName ?
          // Blue.
          `https://lycee-tcg.com/rule/images/index_4_number${this.number}.jpg` :
          // Red.
          `https://lycee-tcg.com/rule/images/index_2_number${this.number}.jpg`
          ;
      },
    },
  };
</script>

<style scoped lang="scss">

    .number-and-name-container {
        display: flex;
        align-items: flex-start;

        img {
            margin-right: .5em;
            vertical-align: top;
        }
    }

    .number-and-name--name {
        padding-top: .2em;
    }

    .number-and-name-column {
        font-weight: bold;
    }

    td {
        border: 1px solid gray;
        padding: .3em;

        line-height: 1.5;
    }
</style>
