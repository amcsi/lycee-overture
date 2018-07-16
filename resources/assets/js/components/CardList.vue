<template>
    <table v-if="cards">
        <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Ex</td>
            <td>DMG</td>
            <td>AP</td>
            <td>DP</td>
            <td>SP</td>
        </tr>
        </thead>
        <tbody>
        <tr v-for="card in cards.data">
            <td>{{ card.id }}</td>
            <td>{{ card.translation.name }}</td>
            <td>{{ card.ex }}</td>
            <td>{{ card.dmg }}</td>
            <td>{{ card.ap }}</td>
            <td>{{ card.dp }}</td>
            <td>{{ card.sp }}</td>
        </tr>
        </tbody>
    </table>
    <div v-else>Loading...</div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  /** @class Cards */
  export default {
    components: {},
    beforeRouteEnter(to, from, next) {
      next(vm => vm.listCards());
    },
    computed: {
      ...mapState({
        cards: state => state.cards.list,
      }),
    },
    methods: {
      ...mapActions({
        listCards: 'cards/listCards',
      }),
    },
  };
</script>

<style scoped>

</style>
