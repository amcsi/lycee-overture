<template>
    <span v-html="formattedText"></span>
</template>

<script>
  import formatCardText from '../../utils/formatCard';

  /**
   * Card text with card markup e.g. '[T][star][0]'.
   *
   * @class CardText
   **/
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