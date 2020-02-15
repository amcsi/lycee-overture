<template>
    <section>
        <h2>Battle</h2>

        <ol>
            <li>
                <h3>Attack Declaration</h3>
                <p>A player declares an attack with an AF (front row) character while tapping it (turning it
                    sideways).</p>
            </li>
            <li>
                <h3>Defending</h3>
                <p>
                    The opponent player decides whether to defend or not with the DF (rear row) character in the same
                    column as the attacking character.
                </p>
            </li>
            <li>
                <h3>Use support / ability / event</h3>
                <p>
                    Aid character with support, ability, and events!
                </p>
            </li>
            <li>
                <h3>Battle result</h3>
                <p>
                    If defense was declared, the two characters battle. See lower down for details.<br />
                    If not, your discard cards from the top of your opponent's deck equal to the attacking character's
                    DMG.
                </p>
            </li>
        </ol>

        <h3>Attack declaration</h3>

        <p>
            You can declare an attack at any time during your main phase of your turn.<br />
            Designate 1 untapped character in your AF.
            The designated character becomes the attacking character of the battle. Tap it (turn it sideways).
        </p>

        <h3>Defense declaration</h3>

        <p>
            When your opponent declares an attack, you can choose to defend.<br />
            Only an untapped character in the DF of the same column as the attacking character can defend.<br />
            When declaring defense, tap the defending character.

        </p>

        <ul>
            <li>If there is no untapped DF character in the same column as the attacker, the battle progresses without a
                defender.
            </li>
            <li>You can choose not to defend even if you had a DF character.</li>
        </ul>

        <h3>Support declaration</h3>

        <p>Each player can declare "support". They can also declare events and abilities.</p>

        <h4>Support</h4>

        <p>
            During the battle you can "support" participants of the battle by designating ally characters adjacent
            (except diagonal) to the participating character.
        </p>

        <p>
            Only the SP value of the supported character increases the stat value of the supported character.<br />
            The rising stat value is AP if an attacker is supported, DP if a defender is supported.
        </p>

        <p>Any character used for support gets tapped.</p>

        <ul>
            <li>The stat value increased by support is effective until the end of the battle.</li>
            <li>You can declare support as many times as you want during a battle.</li>
        </ul>

        <h4>Battle result</h4>

        <p>The result of the battle differs depending on whether there's a defending character or not.</p>

        <h5>When there is no defending character</h5>
        <p>Destroy cards from the top of the defending player's deck equal to the attacker's DMG.</p>

        <h5>When there is a defending character</h5>
        <p>
            Compare the AP of each character to the other characters' DP.<br />
            If the AP value exceeds the DP value, that character goes "down", and is discarded.
        </p>

        <ul>
            <li>
                For both the attacker and the defender, compare the AP and DP.<br />
                As a result, there's a possibility that both characters go "down", or even neither.
            </li>
            <li>
                If the AP and DP values are the same, that character does not go "down".
            </li>
        </ul>

        <h3>Battle example</h3>

        <p>The result of the battle differs depending on whether there is a defending character or not.</p>

        <cloudinary-fetch-img src="https://lycee-tcg.com/rule/images/index_7_img1.png" />

        <h4>[If there is a defending character]</h4>

        <ol>
            <li>
                A player declares an attack during his turn.<br />
                The designated attacking character is "Rider / Ann Bonnie (AP 2, DP 1)".
            </li>
            <li>
                The defending player designates the defending character: "Rider / Edward Teach (AP 3, DP 0)".
            </li>
            <li>Neither player declares support, abilities or events.</li>
        </ol>

        <el-card>
            <h5>Rider / Ann Bonnie's result</h5>
            <p>
                DP1 (Rider / Ann Bonnie) < AP3 (Rider / Edward Teach)<br />
                Rider / Ann Bonnie goes down.
            </p>
        </el-card>

        <el-card>
            <h5>Rider / Edward Teach's result</h5>
            <p>
                DP 0 (Rider / Edward Teach) < AP 2 (Rider / Ann Bonnie)<br />
                Rider / Edward Teach goes down.
            </p>
        </el-card>

        <h4>[When there is no defending character]</h4>

        <ol>
            <li>
                A player declares an attack during his turn.<br />
                The designated attacking character is "Rider / Ann Bonnie (DMG 2, AP 2, DP 1)".
            </li>
            <li>Defending player does not specify a character to defend.</li>
            <li>Neither player declares support, abilities or events.</li>
            <li>Opponent discards 2 cards from the top of his deck.</li>
        </ol>

        <h4>[When there is support declared]</h4>

        <div class="card-image-with-numbered-bullets-on-side">
            <cloudinary-fetch-img src="https://lycee-tcg.com/rule/images/index_7_img3.png" />
            <ol>
                <li>
                    A player declares an attack during his turn.<br />
                    The designated attacking character is "Rider / Edward Teach (AP 3, DP 0)".
                </li>
                <li>
                    The defending player designates the defending character: "Rider / Ann Bonnie (DMG 2, AP 2, DP 1)".
                </li>
                <li>
                    The defending player declared support for "Mary Lead (SP 2)" which is to the left of "Rider / Ann
                    Bonnie (AP 2, DP 1)".<br />
                    Add the SP value (2) of "Mary Lead" to the DP of "Rider / Ann Bonnie (AP 2, DP 1)" (1 + 2 = 3).
                </li>
            </ol>
        </div>

        <el-card>
            <h5>Rider / Ann Bonnie's result</h5>
            <p>
                DP 3 (1 + 2) (Rider / Ann Bonnie) = AP 3 (Rider / Edward Teach)<br />
                Rider / Anne Bonnie will not go down.<br />
                <small>* If the values ​​of DP and AP are the same, the character does not go down.</small>
            </p>
        </el-card>

        <el-card>
            <h5>Rider / Edward Teach's result</h5>
            <p>
                DP 0 (Rider / Edward Teach) < AP 2 (Rider / Ann Bonnie)<br />
                Rider / Edward Teach goes down.
            </p>
        </el-card>
    </section>
</template>

<script>
import CloudinaryFetchImg from '../common/CloudinaryFetchImg';

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
