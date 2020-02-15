<template>
    <section>
        <h2>Basic abilities</h2>

        <p>
            There are characters that have things such as [Side Step], [Aggressive] etc. written on them.<br />
            These are "basic abilities" and give various effects to to characters.
        </p>

        <cloudinary-fetch-img src="https://lycee-tcg.com/rule/images/index_8_img1.png" />

        <table>
            <tr>
                <th>Aggressive</th>
                <td>
                    <CardText text="This character can make a declaration of attack or pay [T] even in the turn it is summoned." />
                </td>
            </tr>
            <tr>
                <th>Step</th>
                <td>
                    Move this character forward, backward, left or right by one.<br />
                    <ul>
                        <li>Only once per turn, and the character must be untapped.</li>
                        <li>Can not be used during battle.</li>
                        <li>Can not move to a field already occupied, nor to your opponent's side.</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Side Step</th>
                <td>Same as "Step", but you can only move left or right.</td>
            </tr>
            <tr>
                <th>Order Step</th>
                <td>Same as "Step", but you can only move up or down.</td>
            </tr>
            <tr>
                <th>Order Change</th>
                <td>
                    Same as "Order Step", but instead of moving, change places with an ally character of the same
                    column. That character may be in tapped position.
                </td>
            </tr>
            <tr>
                <th>Engage</th>
                <td>
                    <p>
                        When you want to summon this character, you can discard an ally character and have this
                        character be summoned to its place.<br />
                        When the ally character is "Engage summoned" as such, the effect described on the right side of
                        the engage is gained.
                    </p>

                    <p>
                        * You can choose to not Engage summon and just summon the character normally. In that case the
                        character does not gain the "engage effect".
                    </p>
                </td>
            </tr>
            <tr>
                <th>Recovery</th>
                <td>
                    <p>
                        When the character is summoned, if the number of characters on your field are less than your
                        opponent's characters, the described effect is gained.
                    </p>

                    <p>
                        ※ Include this character when counting the number of your characters. Also if the numbers are
                        the same, you do not gain the recovery effect.
                    </p>
                </td>
            </tr>
            <tr>
                <th>Leader</th>
                <td>
                    <p>

                        At the start of the game, you must remove this character from your deck. After drawing your
                        first 6 cards, add this character to your hand.
                    </p>

                    <p>
                        ※ The Leader character remains part of your 60-card deck. You may only have 1 Leader character
                        in your deck.
                    </p>
                </td>
            </tr>
            <tr>
                <th>Supporter</th>
                <td>
                    <p>
                        Once per turn you can declare "support" with this character by paying the written cost instead
                        of tapping it.
                    </p>

                    <p>
                        ※ This character must be untapped to use this basic ability.
                    </p>
                </td>
            </tr>
        </table>

        <h2>Chaining</h2>

        <p>
            When player A makes a declaration, the other player B can declare events and abilities as a response the
            declaration of player A.
            (Or Player A may then in turn respond with a further declaration).
            Such a declaration is called "chaining", and each effect will be resolved in the order of the later declared
            gets resolved first.
        </p>

        <ul>
            <li>You cannot declare in response to your own declaration.</li>
            <li>The "Supporter" basic ability can be chained.</li>
            <li>
                The parts that are described in yellow letters in the text of the effect, such as abilities and events,
                indicate the target.<br />
                You must specify such targets when declaring the effect.
            </li>
        </ul>
    </section>
</template>

<script>
import CardText from '../card/CardText';
import CloudinaryFetchImg from '../common/CloudinaryFetchImg';

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

    td, th {
        border: 1px solid gray;
        padding: .5em;
    }
</style>
