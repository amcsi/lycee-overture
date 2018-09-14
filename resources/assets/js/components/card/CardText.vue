<template>
    <span v-html="formattedText"></span>
</template>

<script>
  import formatCardText from '../../utils/formatCard';

  /** @class CardText */
  export default {
    name: 'CardText',
    props: {
      text: String,
    },
    computed: {
      formattedText() {
        return formatCardText(this.text);
      },
    },
  };
</script>