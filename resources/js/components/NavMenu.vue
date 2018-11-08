<template>
    <el-menu router mode="horizontal" :default-active="$route.path">
        <el-menu-item index="/">
            <router-link to="/">{{ $t('nav.welcome') }}</router-link>
        </el-menu-item>
        <el-menu-item index="/rules">
            <router-link to="/rules">{{ $t('nav.rules') }}</router-link>
        </el-menu-item>
        <el-menu-item index="/cards">
            <router-link to="/cards">{{ $t('nav.cardList') }}</router-link>
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
