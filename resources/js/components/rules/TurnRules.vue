<template>
    <section>
        <h2>Turn</h2>

        <p>The turn is done in the following order:</p>

        <el-card>
            <h3 slot="header">Start phase</h3>

            <div>
                <h4>Wake up</h4>
                <p>Untap all your characters.</p>

                <h4>Warm up</h4>
                <p>Draw 2 cards from your deck (and add them to your hand).</p>
            </div>
        </el-card>

        <el-card>
            <h3 slot="header">Main phase</h3>

            <div>
                <h4>You can...</h4>
                <ul>
                    <li>
                        Use a card from your hand
                        <ul>
                            <li>Summon a character</li>
                            <li>Use an event</li>
                            <li>Equip an item</li>
                        </ul>
                    </li>
                    <li>Use the ability of one of your characters</li>
                    <li>Attack</li>
                </ul>

                <h4>Your opponent can...</h4>
                <ul>
                    <li>Use events</li>
                    <li>Use the ability of one of his characters</li>
                </ul>
            </div>
        </el-card>

        <el-card>
            <h3 slot="header">End phase</h3>

            <div>
                <h4>Hand adjustment</h4>

                When you have 8 or more cards in your hand, discard cards until you have 7.

                <p>Any changes to your characters' stats get reset.</p>
            </div>
        </el-card>

        <h2>Start phase</h2>

        <p>The following two steps happen:</p>

        <h3>【1. Wake up】</h3>

        <p>
            Untap all your characters (ally characters) on the field. (Make them vertical).
            I will make all of the characters (ally characters) appearing in my field unplayed. (We will make the card
            vertical)
        </p>

        <h3>【2. Warm up】</h3>

        <p>Draw 2 cards from your deck and add them to your hand.</p>
        <p>The starting player on his first turn only draws 1 card.</p>

        <h2>Main phase</h2>

        <p>
            In the main phase you can make various declaration using cards in your hand and your characters on the
            field.<br />
            You can make these declaration any number of times and in any order you like.<br />
            If you do not declare any more, end the main phase and move on to the end phase.
        </p>

        <h3>Use your hand</h3>

        <h4>Summon characters</h4>

        <p>Place a character on the field.</p>

        <ul>
            <li>
                The character gets placed into the field of your choice on your side of the board.<br />
                Specify which field the character gets summoned to during this declaration.<br />
                Only 1 character can be in 1 field.
            </li>
            <li>Only 1 character with the same number can be on your side of the board at a time.</li>
            <li>All characters are summoned untapped.</li>
            <li>
                You cannot declare an attack or use any ability with a [T] cost with a character you summoned until your
                next turn.<br />
                You can still block attacks or support though.
            </li>
        </ul>

        <h4>Use events</h4>

        <p>Use event cards from your hand for various effects. Event cards that are used get discarded.</p>

        <ul>
            <li>You can use event cards even during your opponent's turn</li>
        </ul>

        <h4>Use items</h4>

        <p>
            You can equip a character on the field with an item from your hand.<br />
            Place the item by the character you equip so your opponent could know which character is equipped.
        </p>

        <ul>
            <li>A character can only be equipped with 1 item.</li>
            <li>You can even equip an opponent's character.</li>
        </ul>

        <h4>Use a special ability of a character</h4>

        <p>
            You can use a special ability of a character of yours on the field.
        </p>

        <ul>
            <li>
                <CardText text="Unless otherwise stated, any ability that's [Activate] or [Trigger] cannot be used more than once per turn." />
            </li>
            <li>
                <CardText text="Any ability that does not show a cost is always active ([Continuous])." />
                <br />
                (The player can not use it, the effect is automatically and forcibly processed).
            </li>
            <li>Abilities can be used during your opponent's turn as well.</li>
        </ul>

        <h4>Use a basic ability of a character</h4>

        <p>
            You can use a basic ability of a character of yours on the field.
        </p>

        <ul>
            <li>Please see the {{ $t('rules.basicAbilityRulesTitle') }} section on more details about basic abilities.
            </li>
        </ul>

        <h4>Attack</h4>

        <p>
            You can attack with any character in your AF.<br />
            For details about attacking, see the explanation in the next section labelled "{{
            $t('rules.battleRulesTitle') }}"
        </p>

        <h3>End phase</h3>

        <p>
            Your turn is over when your main phase ends. If the number of cards in your hand is 8 or more, discard until
            you only have 7 cards left in your hand. Also, if the character's stat value (DMG, AP, DP, SP) has changed
            due to an effect, such as an event or ability, reset those ability values ​​to their original values.
            Any e.g. basic abilities gained by an effect are lost.<br />
            (They will not be lost if you are gaining from a continuous effect.)
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

<style scoped>

</style>
