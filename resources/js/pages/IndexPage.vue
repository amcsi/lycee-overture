<template>
    <div>
        <h1>
            {{ $t('welcome.websiteTitle' )}}
        </h1>

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
            <li>
                <ExternalLink href="https://discord.gg/xtcQDtQ">
                    <img
                        class="discord-icon"
                        src="https://discordapp.com/assets/f8389ca1a741a115313bede9ac02e2c0.svg"
                    >
                    Discord server
                </ExternalLink>
            </li>
            <li>
                <ExternalLink href="https://lycee-tcg.com/">Japanese Official Lycee Overture website</ExternalLink>
            </li>
            <li>
                <ExternalLink href="https://www.facebook.com/groups/205241722843396/">
                    Facebook group (unofficial)
                </ExternalLink>
            </li>
            <li>
                <ExternalLink href="https://lycdb.szeremi.org/">Old Lycee TCG translation website</ExternalLink>
            </li>
            <li>
                <ExternalLink href="http://akiba.geocities.jp/lycee_tcg/">Old Lycee TCG rules website</ExternalLink>
            </li>
            <li>
                <ExternalLink href="https://en.wikipedia.org/wiki/Lyc%C3%A8e_Trading_Card_Game">
                    Old Lycee TCG wikipedia page
                </ExternalLink>
            </li>
            <li>
                <ExternalLink href="https://static.szeremi.org/untranslated-lycee-overture/">
                    All the untranslated cards on one static page
                </ExternalLink>
            </li>
            <li>
                <ExternalLink href="https://static.szeremi.org/translated-lycee-overture/">
                    Auto-translation of effects seen on one static page
                </ExternalLink>
            </li>
        </ul>
    </div>
</template>

<script>
  import ExternalLink from '../components/common/ExternalLink';
  import NavMenu from '../components/NavMenu';

  /** @class IndexPage */
  export default {
    components: { ExternalLink, NavMenu },
  };
</script>

<style scoped>
    .discord-icon {
        width: 1.5em;
        height: 1.5em;
        vertical-align: middle;
    }
</style>
