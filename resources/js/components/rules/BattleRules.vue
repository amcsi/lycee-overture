<template>
  <section>
    <h2>Battle</h2>

    <ol>
      <li>
        <h3>Declare Attack</h3>
        <p>The player taking his turn declares an attack with 1 character in his AF (front row).</p>
      </li>
      <li>
        <h3>Declare Defense</h3>
        <p>
          The opposing player decides whether or not to defend with his character in his DF (back
          row) in the same column as the attacking character.
        </p>
      </li>
      <li>
        <h3>Use Support / Abilities / Events</h3>
        <p>Both players can use Support/Abilities/Events to aid their respective characters.</p>
      </li>
      <li>
        <h3>Battle Result</h3>
        <p>
          If a defender is declared, conduct battle.<br />
          If no defender is declared, the opponent (defending player) receives damage to his deck.
          The opponent player discards cards from the top of his deck equal to the DMG value of the
          attacking character.
        </p>
      </li>
    </ol>

    <h3>Declaring Attack</h3>

    <p>
      The player taking his turn can declare an attack anytime during his main phase.<br />
      To do so, select 1 untapped character in your AF. Tap that character. That character is now
      the attacking battling character.
    </p>

    <h3>Declaring Defense</h3>

    <p>
      If an attack was declared, the defending player can designate a defending character.<br />
      (You can also choose not to defend)<br />
      The defending character is the defending player's untapped DF character in the same column as
      the attacking character.<br />
      Tap the defending character to declare defense.
    </p>

    <ul>
      <li>
        If there are no suitable defending characters, the battle continues without a defender.
      </li>
      <li>
        If there are suitable defending characters, the defending player can also choose not to
        defend. The battle then continues without a defender.
      </li>
    </ul>

    <h3>Declaring Support</h3>

    <p>At this point in battle, both players can declare Support, Abilities and Events.</p>

    <h4>[Support]</h4>

    <p>
      During battle, players can Support battling characters with characters adjacent (except
      diagonal) to the battling characters.
    </p>

    <p>
      When a support is declared, the supporting character's SP is added to the stat value of the
      character being supported.<br />
      The stat value that is added is dependent on the role of the supported character during
      battle. If the aformentioned character is attacking, increase AP; if defending, increase DP.
    </p>

    <p>Characters used for support get tapped.</p>

    <ul>
      <li>Stat values increased by support is effective until the end of the battle.</li>
      <li>Support can be declared as many times as desired in a single battle.</li>
    </ul>

    <h3>Battle Result</h3>

    <p>
      The battle resolves differently based on whether or not a defending character was declared.
    </p>

    <h4>[No Defending Character]</h4>
    <p>
      The defending player discards cards from the top of his deck equal to the DMG value of the
      attacking character.
    </p>

    <h4>[With Defending Character]</h4>
    <p>
      Compare AP and DP values of the battling characters.<br />
      If AP is higher than DP, that character is "downed" (destroyed).
    </p>

    <ul>
      <li>
        The AP and DP is compared between both attacking and defending characters.<br />
        This means that both or even neither characters can be downed at the end of the battle.
      </li>
      <li>If AP and DP are equal, the character does not get downed.</li>
    </ul>

    <h3>Battle Example</h3>

    <p>
      Let's use the cards below to illustrate the multiple battle scenarios. Remember the battle
      resolves differently based on whether or not a defending character was declared.
    </p>

    <cloudinary-fetch-img src="https://lycee-tcg.com/rule/images/index_7_img1.png" />

    <h4>[With Defending Character]</h4>

    <ol>
      <li>
        The player taking his turn declares an attack.<br />
        He designates "Rider / Anne Bonny (AP 2/DP 1)" as his attacking character.".
      </li>
      <li>
        The defending player designates his defending character, "Rider / Edward Teach (AP 3/DP
        0)"..
      </li>
      <li>Neither player uses Supports / Abilities / Events.</li>
    </ol>

    <el-card>
      <h4>Result of Rider / Anne Bonny</h4>
      <p>
        DP1 (Rider / Anne Bonny) &lt; AP3 (Rider / Edward Teach)<br />
        Rider / Anne Bonny is downed
      </p>
    </el-card>

    <el-card>
      <h4>Result of Rider / Edward Teach</h4>
      <p>
        DP0 (Rider / Edward Teach) &lt; AP2 (Rider / Anne Bonny)<br />
        Rider / Edward Teach is downed
      </p>
    </el-card>

    <h4>[No Defending Character]</h4>

    <ol>
      <li>
        The player taking his turn declares an attack.<br />
        He designates "Rider / Anne Bonny (DMG 2/AP 2/DP 1)" as his attacking character.
      </li>
      <li>The defending player does not designate a defending character.</li>
      <li>Neither player uses Supports/Abilities/Events.</li>
      <li>
        Defending player discards 2 cards from the top of his deck (based on the attacking
        character's DMG value).
      </li>
    </ol>

    <h4>[With Support]</h4>

    <div class="card-image-with-numbered-bullets-on-side">
      <cloudinary-fetch-img src="https://lycee-tcg.com/rule/images/index_7_img3.png" />
      <ol>
        <li>
          The player taking his turn declares an attack.<br />
          He designates "Rider / Edward Teach (AP 3/ DP 0)" as his attacking character.
        </li>
        <li>
          The defending player designates his defending character, "Rider / Anne Bonny (AP 2/DP 1)".
        </li>
        <li>
          The defending player declares support with "Rider / Mary Read (SP 2)", who is located on
          the left of "Rider / Anne Bonny (AP 2/DP 1)".<br />
          Mary Read's SP2 is added to the defending Anne Bonny's DP (1 + 2 = 3).
        </li>
      </ol>
    </div>

    <el-card>
      <h4>Result of Rider / Anne Bonny</h4>
      <p>
        DP3 (Rider / Anne Bonny) = AP3 (Rider / Edward Teach)<br />
        Rider / Anne Bonny is not downed<br />
        <small>※ If AP and DP are equal, the character does not get downed</small>
      </p>
    </el-card>

    <el-card>
      <h5>Result of Rider / Edward Teach</h5>
      <p>
        DP0 (Rider / Edward Teach) &lt; AP2 (Rider / Anne Bonny)<br />
        Rider / Edward Teach is downed
      </p>
    </el-card>
  </section>
</template>

<script>
import CloudinaryFetchImg from '../common/CloudinaryFetchImg.vue';

/** @class BattleRules */
export default {
  name: 'BattleRules',
  components: { CloudinaryFetchImg },
};
</script>

<style scoped>
.card-image-with-numbered-bullets-on-side {
  display: flex;
}
</style>
