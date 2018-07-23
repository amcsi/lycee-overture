<template>
    <el-container class="container">
        <el-header height="auto">
            <NavMenu />

            <h1>
                Lycee Overture TCG Translation Website
            </h1>
        </el-header>
        <el-main>

            <h3>
                <router-link to="/cards">View cards</router-link>
            </h3>

            <p>
                The Lycee Trading Card game has been rebooted since the end of 2016 with the new name: Lycee Overture.
            </p>
            <p>
                This is a website in progress with the goal of translating the this new Lycee Overture Trading Card
                Game, and
                also provide a useful database for being able to search for cards. You can view what's been done so far
                in the above link.
            </p>

            <p>
                To make the job of translators easier, I <em>automatically translate</em> a lot of the description texts
                with the help of pattern matching in Japanese sentences. This is still in progress.
            </p>

            <p>
                Relevant links:
            </p>

            <ul>
                <li><a target="_blank" href="https://lycee-tcg.com/">Japanese Official Lycee Overture website</a></li>
                <li><a target="_blank" href="https://www.facebook.com/groups/205241722843396/">Facebook group
                    (unofficial)</a></li>
                <li><a target="_blank" href="https://lycdb.szeremi.org/">Old Lycee TCG translation website</a></li>
                <li><a target="_blank" href="http://akiba.geocities.jp/lycee_tcg/">Old Lycee TCG rules website</a></li>
                <li><a target="_blank" href="https://en.wikipedia.org/wiki/Lyc%C3%A8e_Trading_Card_Game">Old Lycee TCG
                    wikipedia page</a></li>
                <li><a target="_blank" href="https://static.szeremi.org/untranslated-lycee-overture/">All the
                    untranslated cards on one static page</a></li>
                <li><a target="_blank" href="https://static.szeremi.org/translated-lycee-overture/">Auto-translation of
                    effects seen on one static page</a></li>
            </ul>
        </el-main>
        <el-footer>
            <Footer />
        </el-footer>
    </el-container>
</template>

<script>
  import Footer from '../components/Footer';
  import Mailchimp from '../components/Mailchimp';
  import NavMenu from '../components/NavMenu';

  /** @class IndexPage */
  export default {
    components: { Footer, Mailchimp, NavMenu },
  };
</script>

<style scoped>
</style>
