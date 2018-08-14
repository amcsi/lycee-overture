<template>
    <CardListContainer>
        <CardListPrint />
    </CardListContainer>
</template>

<script>
  import CardListPrint from '../components/CardListPrint';
  import CardListContainer from '../components/container/CardListContainer';

  /** @class CardListPrintPage */
  export default {
    name: 'CardListPrintPage',
    components: { CardListContainer, CardListPrint },
  };
</script>

<style scoped>

</style>
