<template>
    <span v-html="cardHtml"></span>
</template>

<script>
  import formatCardText from '../../utils/formatCard';

  /** @class CardDescription */
  export default {
    name: 'CardDescription',
    props: {
      translation: { required: true },
    },
    computed: {
      cardHtml() {
        const { ability_cost, ability_description, basic_abilities, comments } = this.translation;

        let text = '';
        if (basic_abilities) {
          text += `<span class="basic_abilities">${formatCardText(basic_abilities)}</span><br />`;
        }
        if (ability_cost) {
          text += `<span>${formatCardText(ability_cost)}</span>: `;
        }
        text += formatCardText(ability_description);
        if (comments) {
          text += `<br /><span class="comments">${comments}</span>`;
        }
        return text;
      },
    },
  };
</script>

<style scoped>

</style>
