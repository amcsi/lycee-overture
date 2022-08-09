<template>
  <div>
    <h1>Help Translate</h1>

    <div>
      <p>
        Although parts of card names and parts of card text are translated automatically with the
        help of pattern matching and some external services, it can only take us so far. Also
        although I created this site, I don't speak Japanese well enough to translate the rest of
        the cards myself. Thus I need your help!
      </p>

      <p>Below you can find in what ways you can contribute to the translations on the website.</p>
    </div>

    <h3 id="card-names-character-types">Card Names, Ability Names, Character Types</h3>

    <p>
      Just to clarify... I say "Ability Names", but I'm actually not that certain what they are. I
      asked NepZone and he said they are some sort of flavor texts. So if someone can help clarify
      what those means, that's a start :D
    </p>

    <p id="onesky-reference">
      In any case to translate these, please visit this project's
      <ExternalLink :href="oneSkyProjectUrl"> OneSky </ExternalLink>
      page and sign up. This way you can start suggesting translations for these, which I can
      review, then sign off.
    </p>

    <p>Things to keep in mind:</p>

    <ul>
      <li>
        You don't have to speak Japanese well to translate these. Any combination of knowing the
        source material or Googling the name would work as well. I only suggest this for names (and
        only of characters) and character types though; Names of items/events and ability names
        would be quite difficult without knowing decent Japanese.
      </li>
      <li>
        When translating names, if please keep the original name orderings. So in standard Japanese
        names (most of the cards), last name comes first. Please respect that order.
      </li>
      <li>
        There are translatables with "／" and "・" characters in them. In those cases each component
        of the translatable separated by these characters are translatable individually.
      </li>
      <li>
        If you have trouble finding context, I suggest you use the Card List's search functionality
        to find the translatable. Switch the site language to Japanese on the top right of the
        website if necessary.
      </li>
    </ul>

    <h3 id="card-descriptions">Card Descriptions</h3>

    <p>
      You need to register and log in on this site to be able to submit card descrption changes. You
      can do so by manually typing in the /register and /login paths in the URL respectively. The
      reason I didn't include links is to prevent bots from finding the register/login pages.
    </p>

    <p>Things to keep in mind:</p>

    <ul>
      <li>
        Do not translate card names and types between quotes before translating them in OneSky.
        Refer to <a href="#card-names-character-types">this section</a>. This is in order to try to
        keep the quoted names/types in sync with the names/types of the cards in order to be able to
        do neat things like automatic links from quoted text to list the cards that match the quote.
        <ul>
          <li>
            Generally text between angled brackets (&lt;like this&gt;) always refer to character
            types. Please keep these in sync with OneSky.
          </li>
          <li>
            Text between quotation marks (“Like this”) can be one of these things:
            <ul>
              <li>
                A reference to a card name. By clicking on the link of such a quote, if you get a
                card(s) listed, then it's definitely a card name. So make sure to translate the name
                of that card in OneSky first.
              </li>
              <li>
                A reference to an anime/light novel (e.g. "untap 1 “ToHeart2” character"). You can
                freely translate these without worrying about OneSky.
              </li>
              <li>
                A reference to the name of a character token. You can tell by the last card
                description row mentioning that same character token name and with that token's
                stats. Feel free to translate this without worrying about OneSky.
              </li>
            </ul>
          </li>

          <li>
            There may be quotes that don't refer to actual card names/types. In that case feel free
            to just translate them.
          </li>
          <li>
            Generally text between angled brackets (&lt;like this&gt;) always refer to character
            types. Text between quotation marks (“Like this”) are
          </li>
        </ul>
      </li>
      <li>
        Please try to keep the symbols, quotes, targeting etc. intact. Take advantage of the offered
        card text preview functionality for this.
      </li>
      <li>
        Do not translate cards/sentences that are already fully translated thanks to automatic
        translation. The text may not be perfect, but it's best to rely on automatic translations
        whenever possible so e.g. in case of the card text getting errata'd, the auto-translation
        will follow it unlike the manual translation.
        <ul>
          <li>
            In case you think a fully auto-translated sentence is outright wrong, please let me know
            so I can sort it out.
          </li>
          <li>
            However if you encounter a <em>partly</em> auto-translated sentence, then feel free to
            change that all you want, as those are not expected to be taken seriously.
          </li>
        </ul>
      </li>
      <li>
        In general, try to follow the style of translations to all the other automatically or
        manually translated cards to make both the automatic and manually translated cards feel
        natural among one another.
      </li>
      <li>
        If you can't decide which cards to start translation, here is my suggestion:
        <ol>
          <li>Cards in starter decks.</li>
          <li>
            <router-link to="/cards?hideFullyTranslated=1&translatedFirst=1"
              >Cards with fewer untranslated bits.
            </router-link>
          </li>
        </ol>
      </li>
    </ul>

    <p>
      Here is a hopefully helpful glossary that may be helpful for translating whether you are good
      in Japanese or not:
    </p>

    <h4>Glossary</h4>

    <el-table :data="glossaryData">
      <el-table-column prop="0" label="Japanese" width="180"> </el-table-column>
      <el-table-column prop="1" label="English"> </el-table-column>
    </el-table>
  </div>
</template>

<script>
import ExternalLink from '../components/common/ExternalLink.vue';
import { oneSkyProjectUrl } from '../value/env';
import { glossaryData } from '../value/glossary';

/** @class HelpTranslatePage */
export default {
  name: 'HelpTranslatePage',
  components: { ExternalLink },
  data() {
    return {
      glossaryData,
      oneSkyProjectUrl,
    };
  },
  mounted() {
    // Manually scroll ID element into view, bc for some reason it doesn't usually work by itself.

    const hash = window.location.hash;
    let element;
    console.info({ hash });
    if (hash && (element = document.getElementById(hash.substring(1)))) {
      setTimeout(() => element.scrollIntoView(), 200);
    }
  },
};
</script>

<style scoped lang="scss"></style>
