<template>
    <div v-if="cards">
        <Paginator :pagination="cards.meta.pagination" @page-change="pageChange" />

        <el-table
            :data="cards.data"
        >
            <el-table-column
                prop="id"
                label="ID"
            >
            </el-table-column>
            <el-table-column
                prop="translation.name"
                label="Name"
            >
            </el-table-column>
            <el-table-column
                prop="ex"
                label="Ex"
            >
            </el-table-column>
            <el-table-column
                prop="dmg"
                label="DMG"
            >
            </el-table-column>
            <el-table-column
                prop="ap"
                label="AP"
            >
            </el-table-column>
            <el-table-column
                prop="dp"
                label="DP"
            >
            </el-table-column>
            <el-table-column
                prop="sp"
                label="SP"
            >
            </el-table-column>
        </el-table>

        <Paginator :pagination="cards.meta.pagination" :page-change="pageChange" />
    </div>
    <div v-else>Loading...</div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import Paginator from './common/Paginator';

  /** @class Cards */
  export default {
    components: { Paginator },
    beforeRouteEnter(to, from, next) {
      next(vm => vm.listCards(to.query.page));
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
      pageChange(page) {
        this.$router.push({ path: '/cards', query: { page } });
      },
    },
    watch: {
      '$route.query.page'() {
        this.listCards(this.$route.query.page);
      },
    },

  };
</script>

<style scoped>

</style>
