<template>
    <el-container class="container">
        <el-header height="auto">
            <NavMenu />

            <h1>
                Lycee Overture TCG Translation Website
            </h1>
        </el-header>
        <el-main>
            <router-view></router-view>
        </el-main>
        <el-footer>
            Created by
            <ExternalLink href="https://www.szeremi.org/">Attila Szeremi</ExternalLink>
            . Drop me an email at
            <strong>lycee-overture@szeremi.org</strong>, or follow me on
            <ExternalLink href="https://twitter.com/amcsi_san">Twitter</ExternalLink>
            .<br />
            Website source code can be found on
            <ExternalLink
                href="https://github.com/amcsi/lycee-overture"
            >GitHub
            </ExternalLink>
            .<br />
            Thanks to Yee Cheng Xuan for consulting with me with the automatic translations.
        </el-footer>
    </el-container>
</template>

<script>
  import ExternalLink from './components/common/ExternalLink';
  import NavMenu from './components/NavMenu';

  /** @class App */
  export default {
    components: { ExternalLink, NavMenu },
  };
</script>

<style lang="scss">
    @import 'resources/assets/sass/app.scss';
</style>
