<template>
  <section>
    <h2>Basic Abilities</h2>

    <p>
      Many characters in the game have Basic Abilities such as [Side Step], [Aggressive] etc. that
      can be used to achieve various effects during the game.
    </p>

    <cloudinary-fetch-img src="https://lycee-tcg.com/rule/images/index_8_img1.png" />

    <table>
      <tr>
        <th>Aggressive</th>
        <td>
          <CardText
            text="This character can declare attack or use [T] effects on the turn it is summoned."
          />
        </td>
      </tr>
      <tr>
        <th>Step</th>
        <td>
          This character moves one space to adjacent spots (left, right, up, down).<br />
          <ul>
            <li>
              This ability can be used only once during your turn, while this character is untapped.
            </li>
            <li>This ability cannnot be used during battle.</li>
            <li>You cannot move into occupied spaces or the opponent's field.</li>
          </ul>
        </td>
      </tr>
      <tr>
        <th>Side Step</th>
        <td>Same effect as Step, but this character can only move left or right.</td>
      </tr>
      <tr>
        <th>Order Step</th>
        <td>Same effect as Step, but this character can only move up or down.</td>
      </tr>
      <tr>
        <th>Order Change</th>
        <td>
          Same effect as Step, but instead this character swaps position with ally characters in the
          same column (the ally character does not need to be untapped).
        </td>
      </tr>
      <tr>
        <th>Engage</th>
        <td>
          <p>
            When summoning, you can also replace an existing character already on the field. Doing
            it in this manner is also known as an Engage Summon.<br />
            A character with this ability will trigger effects stated in the writing of this ability
            when Engage Summoned.
          </p>

          <p>※ A character with Engage, when summoned normally, will not trigger Engage.</p>
        </td>
      </tr>
      <tr>
        <th>Recovery</th>
        <td>
          <p>
            When this character is summoned, and if your opponent has more characters than you, this
            ability activates.
          </p>

          <p>
            ※ This character is included in the character count for allied characters. Also, if the
            number of characters are equal on both sides, Recovery does not activate.
          </p>
        </td>
      </tr>
      <tr>
        <th>Leader</th>
        <td>
          <p>
            At the start of the game, remove this character from the deck, draw 6 cards, and add
            this character to your hand.
          </p>

          <p>
            ※ The character with Leader is included in the deck total of 60. You may only have one
            Leader in your deck.
          </p>
        </td>
      </tr>
      <tr>
        <th>Supporter</th>
        <td>
          <p>
            You may pay the stated cost instead of tapping this character when declaring Support.
          </p>

          <p>※ This ability can only be used while this character is untapped.</p>
        </td>
      </tr>
      <tr>
        <th>Penalty</th>
        <td>
          <p>When this character leaves the field, trigger this ability.</p>
        </td>
      </tr>
      <tr>
        <th>Guts</th>
        <td>
          <p>
            When this character is downed (and sent to the discard pile), you may pay the stated
            cost to resummon this character, without paying summoning cost, on to any position of
            your field.
          </p>

          <p>
            ※ This ability can only be used when this character is downed at the result of a battle.
          </p>
        </td>
      </tr>
    </table>

    <h2>The Stack</h2>

    <p>
      When Player A declares an action (eg. summoning, attacking, Events, abilities etc.), Player B
      can declare his Events or abilities in response (which Player A can further declare in
      response). This creates what is known as the Stack. The Stack is resolved in reverse order of
      declaration (first in last out).
    </p>

    <ul>
      <li>You cannot declare an action against your own declaration.</li>
      <li>Basic Abilities and Support can be declared against other declarations.</li>
      <li>
        Some abilities and Events have yellow wordings which indicate targets of effects.<br />
        These targets must be determined on declaration.
      </li>
    </ul>
  </section>
</template>

<script>
import CardText from '../card/CardText.vue';
import CloudinaryFetchImg from '../common/CloudinaryFetchImg.vue';

/** @class BasicAbilityRules */
export default {
  name: 'BasicAbilityRules',
  components: { CloudinaryFetchImg, CardText },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
}

td,
th {
  border: 1px solid gray;
  padding: 0.5em;
}
</style>
