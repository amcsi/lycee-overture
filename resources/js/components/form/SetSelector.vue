<template>
    <el-form-item label="Set" v-loading="listLoading">
        <el-select placeholder="-" :value="value" @input="$emit('input', $event)">
            <el-option label="-" value=""></el-option>
            <el-option label="(Unknown or no set)" value="-1"></el-option>
            <el-option
                v-for="item in list"
                :key="item.id"
                :label="item.full_name"
                :value="String(item.id)"
            ></el-option>
        </el-select>
    </el-form-item>
</template>

<script>
import { mapActions, mapState } from 'vuex';

/** @class SetSelector */
export default {
  name: 'SetSelector',
  props: {
    value: [Number, String],
  },
  computed: {
    ...mapState('sets', ['listLoading', 'list']),
  },
  methods: {
    ...mapActions('sets', ['listSets']),
  },
  created() {
    this.listSets();
  },
};
</script>
