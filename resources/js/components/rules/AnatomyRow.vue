<template>
    <tr>
        <td class="number-and-name-column">
            <div class="number-and-name-container">
                <img
                    :src="`https://lycee-tcg.com/rule/images/index_2_number${number}.jpg`"
                    alt="$t('rules.characterCard.numberPointingToCharacterImage')"
                />

                <div class="number-and-name--name">
                    {{ name }}
                </div>
            </div>
        </td>
        <td>
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

    td {
        border: 1px solid gray;
        padding: .3em;

        line-height: 1.5;
    }
</style>
