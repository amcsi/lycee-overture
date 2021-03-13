<template>
  <section>
    <h2>Turn</h2>

    <p>The turn order goes as follows:</p>

    <el-card>
      <h3 slot="header">Start Phase</h3>

      <div>
        <h4>Wake-Up:</h4>
        <p>Untap all your characters.</p>

        <h4>Warm-Up</h4>
        <p>Draw 2 cards from your deck and add them to your hand.</p>
      </div>
    </el-card>

    <el-card>
      <h3 slot="header">Main Phase</h3>

      <div>
        <h4>The player taking his turn can:</h4>
        <ul>
          <li>
            From own hand
            <ul>
              <li>Summon a character</li>
              <li>Use an event</li>
              <li>Equip an item</li>
            </ul>
          </li>
          <li>Use his character abilities</li>
          <li>Attack</li>
        </ul>

        <h4>The other player can:</h4>
        <ul>
          <li>Use events</li>
          <li>Use his character abilities</li>
        </ul>
      </div>
    </el-card>

    <el-card>
      <h3 slot="header">End Phase</h3>

      <div>
        <h4>Hand adjustment</h4>

        If you end your turn with 8 or more cards in your hand, discard until you have 7.

        <p>Reset all character stat changes.</p>
      </div>
    </el-card>

    <h2>Start Phase</h2>

    <p>The start phase consists of the following two steps:</p>

    <h3>【1. Wake-Up】</h3>

    <p>Untap (put verticle) all ally character on your side of the field.</p>
    <p>※ This is a must.</p>

    <h3>【2. Warm-Up】</h3>

    <p>Draw 2 cards from your deck and add them to your hand.</p>
    <p>※ The starting player draws only 1 card on his very first turn.</p>

    <h2>Main Phase</h2>

    <p>
      During the Main Phase, players are allowed to declare plays from cards in their hand or
      characters in their field.<br />
      Players can declare plays as many times and in any order they like.<br />
      When neither player are able nor willing to declare plays, the Main Phase ends and the turn
      moves on to the End Phase.
    </p>

    <h3>[Use From Hand]</h3>

    <h4>[Summon Characters]</h4>

    <p>Place characters on your field.</p>

    <ul>
      <li>
        Summon characters on to a space on your side of the field.<br />
        When declaring summon, specify the space you want your character to be summoned to.<br />
        Each space can hold only 1 character.
      </li>
      <li>Only 1 character with the same card number can be on your field at a time.</li>
      <li>All characters are summoned untapped.</li>
      <li>
        Characters summoned this turn cannot Attack or use [T] Abilities until your next turn.<br />
        However they can still block attacks and/or support.
      </li>
    </ul>

    <h4>[Use Events]</h4>

    <p>
      You can use Event Cards from your hand for various effects. Events are discarded once used.
    </p>

    <ul>
      <li>You can use Events during your opponent's turn.</li>
    </ul>

    <h4>[Equip Items]</h4>

    <p>
      You can use Item Cards from your hand and equip them on to characters on the field.<br />
      Equipped Items should be placed under their respective characters so your opponent will know
      who the Item is equipped to.
    </p>

    <ul>
      <li>Each character can equip only 1 Item.</li>
      <li>You can equip Items on to opponent characters.</li>
    </ul>

    <h4>[Use Character Abilities]</h4>

    <p>You can use the abilities of characters on your field.</p>

    <ul>
      <li>
        <CardText
          text="Unless otherwise stated, [Activate][Trigger] abilities can only be used once a turn."
        />
      </li>
      <li>
        <CardText text="Abilities with no cost shown are always active ([Continuous])." />
        <br />
        (Players cannot declare its use; Effects are automatic and must be resolved.)
      </li>
      <li>Character Abilities can be used during your opponent's Main Phase.</li>
    </ul>

    <h4>[Use Character Basic Abilities]</h4>

    <p>You can use the Basic Abilities of characters on your field.</p>

    <ul>
      <li>
        Refer to "{{ $t('rules.basicAbilityRulesTitle') }}" for more details about Basic Abilities.
      </li>
    </ul>

    <h4>Attack</h4>

    <p>
      You can attack with characters in your AF.<br />
      Refer to "{{ $t('rules.battleRulesTitle') }}" for details about attacking.
    </p>

    <h3>End Phase</h3>

    <p>
      The turn is over when the Main Phase ends. If your hand has 8 or more cards, discard until you
      are left with 7. Also, if character stat values (DMG, AP, DP, SP) have changed due to an
      effect (ability, Events, Items etc.), reset them to their original values. Abilities (basic
      etc.) gained from effects are also lost.<br />
      (Continuous effects such as those generated from Items remain, however.)
    </p>
  </section>
</template>

<script>
import CardText from '../card/CardText';

/** @class TurnRules */
export default {
  name: 'TurnRules',
  components: { CardText },
};
</script>

<style scoped></style>
