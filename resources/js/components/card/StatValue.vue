<template>
  <el-tag class="stat-value" :class="cls" :type="tagType" size="small" hit
    >{{ value }}
    {{ typeCapitals }}
  </el-tag>
</template>

<script>
const tagTypeMap = {
  ex: 'info',
  dmg: 'success',
  ap: 'danger',
  dp: '',
  sp: 'warning',
};

/** @class StatValue */
export default {
  name: 'StatValue',
  props: {
    type: {
      required: true,
      validator(value) {
        return ['ex', 'dmg', 'ap', 'dp', 'sp'].indexOf(value) >= 0;
      },
    },
    value: Number,
  },
  computed: {
    typeCapitals() {
      return this.type.toUpperCase();
    },
    tagType() {
      return tagTypeMap[this.type];
    },
    cls() {
      return { [`stat-value-${this.tagType}`]: true };
    },
  },
};
</script>

<style scoped lang="scss">
@import 'element-plus/theme-chalk/src/common/var.scss';

.stat-value {
  font-weight: bold;
}

$colors: (
  'primary': $color-primary,
  'success': $color-success,
  'info': $color-info,
  'warning': $color-warning,
  'danger': $color-danger,
);

@each $type, $color in $colors {
  .stat-value-#{$type} {
    // For better contrast.
    color: darken($color, 20);
  }
}
</style>
