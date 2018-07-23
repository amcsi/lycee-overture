<template>
    <el-menu router mode="horizontal" :default-active="$route.path">
        <el-menu-item index="/">
            <router-link to="/">Welcome</router-link>
        </el-menu-item>
        <el-menu-item index="/cards">
            <router-link to="/cards">Card List</router-link>
        </el-menu-item>
    </el-menu>
</template>

<script>
  /** @class NavMenu */
  export default {
    components: {},
  };
</script>

<style scoped>

</style>
