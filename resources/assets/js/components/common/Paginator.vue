<template>
    <el-pagination
        :total="pagination.total"
        :current-page="pagination.current_page"
        :page-size="pagination.per_page"
        @current-change="$emit('page-change', $event)"
        layout="prev, pager, next"
    />
</template>

<script>
  /** @class Paginator */
  export default {
    props: {
      pagination: { required: true },
    },
    components: {},
  };
</script>
