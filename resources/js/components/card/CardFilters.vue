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

        <el-form-item :label="$t('cardFilters.cardId')">
            <el-input class="card-id-input" placeholder="LO-0001,LO-0002" v-model="cardId" />
        </el-form-item>

        <router-link :to="{path: 'cards/print', query: $route.query }" v-if="1 <= totalCards && totalCards <= 60">
            <i class="fa fa-print"></i>
            Print view
        </router-link>
    </el-form>
</template>

<script>
  import debounce from 'lodash.debounce';
  import { mapState } from 'vuex';

  /** @class CardFilters */
  export default {
    name: 'CardFilters',
    computed: {
      ...mapState('cardSets', {
        cardSetList: 'list',
      }),
      ...mapState('cards', {
        totalCards: state => state.list.meta.pagination.total,
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
      cardId: {
        get() {
          return this.$route.query.cardId || '';
        },
        set: debounce(function(id) {
          const query = { ...this.$route.query, page: 1 };
          delete query.cardId;
          if (id) {
            query.cardId = id;
          }
          this.$router.push({ query });
        }, 1000),
      },
    },
  };
</script>

<style scoped>
    .card-id-input {
        width: 200px;
    }
</style>
