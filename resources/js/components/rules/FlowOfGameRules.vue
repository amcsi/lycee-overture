<template>
    <div>
        <h2>{{ $t('rules.flowOfGameRulesTitle') }}</h2>

        <h3>Winning the game</h3>

        <p>As soon as the size of your opponent's deck becomes 0, the game ends and you win.</p>

        <h3>Game flow</h3>

        <h4>Preparation before the start of the game</h4>

        <div class="box">
            Shuffle your deck.
        </div>

        <div class="arrow">

        </div>

        <div class="box">
            <strong>The one who won a game of rock-paper-scissors or a dice roll gets to start.</strong><br />
            * The starting player will only draw 1 card during their first warm-up.
        </div>

        <div class="arrow">

        </div>

        <div class="box">
            <strong>Draw 7 cards from your deck and make it your hand.</strong><br />
            ※ You have one optional chance to shuffle the cards back and draw 7 again.
        </div>

        <h3>
            Start the game!
        </h3>

        <p>
            First player starts.<br />
            When your turn ends, your opponent goes next. Alternate turns until the game is over.
        </p>

        <p>As soon as the size of your opponent's deck becomes 0, the game ends and you win.</p>
    </div>
</template>

<script>
  /** @class FlowOfGameRules */
  export default {
    name: 'FlowOfGameRules',
  };
</script>

<style scoped>

</style>
