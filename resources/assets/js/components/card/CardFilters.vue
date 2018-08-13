<template>
    <el-form ref="form" label-width="120px">
        <el-form-item label="Starter deck">
            <el-select placeholder="-" v-model="cardSetId">
                <el-option label="All cards" value=""></el-option>
                <el-option
                    v-for="cardSet in cardSetList"
                    :key="cardSet.id"
                    :label="cardSet.name"
                    :value="'' + cardSet.id"
                ></el-option>
            </el-select>
        </el-form-item>

        <router-link to="cards/print" v-if="cardSetId"><i class="fa fa-print"></i> Print view</router-link>
    </el-form>
</template>

<script>
  import { mapState } from 'vuex';

  /** @class CardFilters */
  export default {
    name: 'CardFilters',
    computed: {
      ...mapState('cardSets', {
        cardSetList: 'list',
      }),
      cardSetId: {
        get() {
          return this.$route.query.set || '';
        },
        set(id) {
          const query = { ...this.$route.query, page: 1 };
          delete query.set;
          if (id) {
            query.set = id;
          }
          this.$router.push({ query });
        },
      },
    },
  };
</script>

<style scoped>

</style>
