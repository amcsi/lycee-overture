<template>
    <el-row>
        <el-col :sm="12">
            <el-pagination
                :total="pagination.total"
                :current-page="pagination.current_page"
                :page-size.sync="limit"
                @current-change="$emit('page-change', $event)"
                layout="total, sizes, prev, pager, next, jumper"
                :page-sizes="limitValues"
                style="margin-bottom: 0.5rem"
                :get-to-for-page="getToForPage"
            />
        </el-col>
    </el-row>
</template>

<script>/** @class Paginator */

import router from '../../router';

  export default {
    props: {
      pagination: { required: true },
    },
    data() {
      return {
        limitDefault: 10,
        limitValues: [10, 20, 50, 100],
      };
    },
    components: {},
    methods: {
      getToForPage(page) {
        return {
          path: this.$route.path,
          query: {...this.$route.query, page},
        };
      }
    },
    computed: {
      limit: {
        get() {
          return this.$route.query.limit || this.limitDefault;
        },
        set(limit) {
          const query = { ...this.$route.query };
          query.limit = limit;
          if (limit === this.limitDefault) {
            // This is the default value.
            delete query.limit;
          }

          this.$router.push({ query });
        },
      },
    },
  };
</script>

<style scoped lang="scss">
    @import '~element-ui/packages/theme-chalk/src/mixins/mixins.scss';

    @include res(sm) {
        .limitContainer {
            text-align: right;
        }
    }
</style>
