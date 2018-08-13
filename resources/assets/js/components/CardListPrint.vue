<template>
    <div class="print-container">
        <div class="no-print">
            <p>
                <router-link to="/cards">Back</router-link>
            </p>

            <p>Note that when printing: turn on "Background Images/graphics" in the printing options to include the
                options!</p>
        </div>

        <div class="print">
            <CardPrint v-for="card in cards" :key="card.id" :card="card" />
        </div>
    </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import CardPrint from './card/CardPrint';

  /** @class CardListPrint */
  export default {
    name: 'CardListPrint',
    components: { CardPrint },
    computed: {
      ...mapState({
        cards: state => state.cards.list.data,
      }),
    },
    methods: {
      ...mapActions({
        doInitialCardTasks: 'doInitialCardTasks',
      }),
    },
  };
</script>

<style scoped>
    .print {
        font-size: 12px;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style>
