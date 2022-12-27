<template>
  <el-form-item label="Starter deck" v-loading="listLoading">
    <el-select
      placeholder="-"
      :model-value="modelValue"
      @change="$emit('update:modelValue', $event)"
    >
      <el-option label="-" value=""></el-option>
      <el-option
        v-for="deck in list"
        :key="deck.id"
        :label="deck.name"
        :value="'' + deck.id"
      ></el-option>
    </el-select>
  </el-form-item>
</template>

<script>
import { mapActions, mapState } from 'vuex';

/** @class DeckSelector */
export default {
  name: 'DeckSelector',
  props: {
    modelValue: [Number, String],
  },
  emits: ['update:modelValue'],
  computed: {
    ...mapState('decks', ['listLoading', 'list']),
  },
  methods: {
    ...mapActions('decks', ['listDecks']),
  },
  created() {
    this.listDecks();
  },
};
</script>
