<template>
  <section>
    <h2>Objective</h2>

    <p>Reduce the size of your opponent's deck to 0.</p>

    <h2>Game Flow</h2>

    <h3>Before starting the game...</h3>

    <div class="box">1) Shuffle your deck.</div>

    <div class="arrow"></div>

    <div class="box">
      2) The one who won a game of rock-paper-scissors or a dice roll gets to start.<br />
      ※ The first player will draw only 1 card on their first Warm-Up.
    </div>

    <div class="arrow"></div>

    <div class="box">
      3) Draw 7 cards from your deck and make it your hand.<br />
      ※ You can choose to, only once, re-shuffle your hand back into your deck and draw 7 again.
    </div>

    <h2>Start the Game!</h2>

    <p>
      First player starts, carries out his turn.<br />
      Second player carries out his turn.<br />
      Repeat until game is over.
    </p>
  </section>
</template>

<script>
/** @class FlowOfGameRules */
export default {
  name: 'FlowOfGameRules',
};
</script>

<style scoped></style>
