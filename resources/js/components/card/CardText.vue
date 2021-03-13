<template>
  <span class="cardText" @click.prevent="onClick" v-html="formattedText"></span>
</template>

<script>
import formatCardMixin from '../../utils/formatCardMixin';

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
  mixins: [formatCardMixin],
  computed: {
    formattedText() {
      return this.formatCardText(this.text);
    },
  },
  methods: {
    onClick($event) {
      if ($event.target.dataset.key === 'name') {
        // Handle click on a name.
        const query = { name: $event.target.dataset.value };
        this.$router.push({ query });
      }
    },
  },
};
</script>

<style>
.cardText a {
  color: inherit;
}
</style>
