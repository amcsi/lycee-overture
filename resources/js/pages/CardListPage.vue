<template>
    <CardListContainer>
        <h1>
            {{ $t('nav.cardList' )}}
        </h1>

        <CardFilters />
        <CardList />
    </CardListContainer>
</template>

<script>
  import CardFilters from '../components/card/CardFilters';
  import CardList from '../components/CardList';
  import CardListContainer from '../components/container/CardListContainer';

  /** @class CardListPage */
  export default {
    name: 'CardListPage',
    components: { CardList, CardFilters, CardListContainer },
  };
</script>

<style scoped>

</style>
