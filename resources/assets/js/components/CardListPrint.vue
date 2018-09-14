<template>
    <div class="print-container">
        <div class="no-print">
            <p>
                <router-link :to="{ path: '/cards', query: $route.query }">Back</router-link>
            </p>

            <p>Note that when printing: turn on "Background Images/graphics" in the printing options to include the
                options!</p>

            <p>It's recommended to print in color.</p>

            <p>These help messages will <em>not</em> appear in the printed page.</p>

            <p>
                <el-switch v-model="withImages" />
                With images
            </p>
        </div>

        <div class="print">
            <CardPrint :withImages="withImages" v-for="card in cards" :key="card.id" :card="card" />
        </div>
    </div>
</template>

<script>
  import { mapActions } from 'vuex';
  import CardPrint from './card/CardPrint';

  /** @class CardListPrint */
  export default {
    name: 'CardListPrint',
    components: { CardPrint },
    props: {
      cards: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        withImages: true,
      };
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
