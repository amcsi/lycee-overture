<template>
  <div style="display: flex">
    <el-input type="textarea" rows="20" :value="source" @input="$emit('update:source', $event)" />
    <el-input type="textarea" rows="20" readonly :value="result" />
  </div>
</template>

<script>
/** @class DeckToMigrationConverter */
export default {
  name: 'DeckToMigrationConverter',
  props: {
    source: {
      type: String,
      required: true,
    },
  },
  computed: {
    result() {
      const lines = this.source.split('\n');
      // Get all the matches with card numbers and quantities in them.
      const pairsOfCardNumberAndQuantity = lines
        .map(line => line.match(/(LO-\d{4})\b.*\b(\d)\b/) || line.match(/\b(\d)\b.*\b(LO-\d{4})\b/))
        // Filter out nulls (non-matches).
        .filter(v => v)
        // Remove the entire line match portion.
        .map(v => v.slice(1))
        // If the quantity is on the first element of the array, then reverse the array.
        .map(v => (String(Number(v[0])) === v[0] ? [v[1], v[0]] : v));
      const cardCount = pairsOfCardNumberAndQuantity.reduce(
        (acc, current) => acc + Number(current[1]),
        0
      );

      if (!pairsOfCardNumberAndQuantity.length) {
        return '(No results)';
      }

      const ret = [];

      if (cardCount !== 60) {
        ret.push(`Warning! Card count is ${cardCount}.`);
      }

      // Format the cards in the deck in a way that's appropriate for Laravel migrations.
      ret.push('[');
      for (const [cardNumber, quantity] of pairsOfCardNumberAndQuantity) {
        ret.push(`    ['${cardNumber}', ${quantity}],`);
      }
      ret.push(']');

      return ret.join('\n');
    },
  },
};
</script>

<style scoped lang="scss"></style>
