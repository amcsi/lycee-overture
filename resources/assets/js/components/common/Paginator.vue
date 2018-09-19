<template>
    <el-row>
        <el-col span="12">
            <el-pagination
                :total="pagination.total"
                :current-page="pagination.current_page"
                :page-size="pagination.per_page"
                @current-change="$emit('page-change', $event)"
                layout="prev, pager, next"
            />
        </el-col>

        <el-col span="12" class="limitContainer">
            <select v-model="limit" class="right">
                <option v-for="limitValue in limitValues" :value="limitValue">
                    {{ limitValue }}
                </option>
            </select>
            items per page
        </el-col>
    </el-row>
</template>

<script>
  /** @class Paginator */
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

<style scoped>
    .limitContainer {
        text-align: right;
    }
</style>