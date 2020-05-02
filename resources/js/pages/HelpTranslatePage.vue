<template>
    <div>
        <h1>Help Translate</h1>

        <div>
            <p>Although parts of card names and parts of card text are translated automatically with
                the help of pattern matching and some external services, it can only take us so far.
                Also although I created this site, I don't speak Japanese well enough to translate
                the rest of the cards myself. Thus I need your help!</p>

            <p>Below you can find in what ways you can contribute to the translations on the
                website.</p>
        </div>

        <h3>Card Names, Ability Names, Character Types</h3>

        <p>Just to clarify... I say "Ability Names", but I'm actually not that certain what they
            are. I asked NepZone and he said they are some sort of flavor texts.
            So if someone can help clarify what those means, that's a start :D
        </p>

        <p>In any case to translate these, please visit this project's
            <ExternalLink href="https://oscet9a.oneskyapp.com/collaboration/translate/project/project/314275/language/1">
                OneSky
            </ExternalLink>
            page and sign up. This way you can start suggesting translations for these, which I can
            review, then sign off.
        </p>

        <p>Things to keep in mind:</p>

        <ul>
            <li>
                You don't have to speak Japanese well to translate these. Any combination of knowing
                the source material or Googling the name would work as well. I only suggest this for
                names (and only of characters) and character types though; Names of items/events and
                ability names would be quite difficult without knowing decent Japanese.
            </li>
            <li>When translating names, if please keep the original name orderings. So in standard
                Japanese names (most of the cards), last name comes first. Please respect that
                order.
            </li>
            <li>There are translatables with "／" and "・" characters in them. In those cases each
                component of the translatable separated by these characters are translatable
                individually.
            </li>
            <li>If you have trouble finding context, I suggest you use the Card List's search
                functionality to find the translatable. Switch the site language to Japanese on the
                top right of the website if necessary.
            </li>
        </ul>

        <h3>Card Descriptions</h3>

        <p>Unfortunately the website is not yet ready for translating the actual card descriptions.
            Various preparations need to be done, the existing automatic translations needs to be
            finalized, and rules of translations would need to be assembled among other things.</p>
    </div>
</template>

<script>
import ExternalLink from '../components/common/ExternalLink';

/** @class HelpTranslatePage */
export default {
  name: 'HelpTranslatePage',
  components: { ExternalLink },
};
</script>

<style scoped lang="scss">

</style>
