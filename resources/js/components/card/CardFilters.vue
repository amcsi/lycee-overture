<template>
  <el-form inline ref="form" label-width="120px">
    <SetSelector v-model="set" />

    <el-form-item label="Brand">
      <el-select placeholder="-" v-model="brand">
        <el-option label="-" value=""></el-option>
        <el-option
          v-for="{ value, label } in _brands"
          :key="value"
          :label="label"
          :value="value"
        ></el-option>
      </el-select>
    </el-form-item>

    <DeckSelector v-model="deck" />

    <el-form-item :label="$t('cardFilters.cardId')">
      <a-input class="card-id-input" placeholder="LO-0001,LO-0002" v-model="cardId" />
    </el-form-item>

    <el-form-item :label="$t('cardFilters.nameOfCard')">
      <a-input class="card-id-input" v-model="name" />
    </el-form-item>

    <el-form-item :label="$t('cardFilters.cardText')">
      <a-input class="card-id-input" v-model="text" />
    </el-form-item>

    <div style="clear: both"></div>

    <el-form-item>
      <a-checkbox
        v-model="translatedFirst"
        true-label="1"
        :label="$t('cardFilters.translatedFirst')"
      />
    </el-form-item>

    <el-form-item>
      <a-checkbox v-model="hideFullyTranslated" true-label="1" label="Hide fully translated" />
    </el-form-item>

    <el-form-item>
      <a-checkbox
        v-model="translationSuggestions"
        true-label="1"
        label="Cards with unapproved translations"
      />
    </el-form-item>

    <el-form-item>
      <a href="#" @click.prevent="clearAllFilters"
        ><i class="fa fa-eraser"></i> Clear all filters</a
      >
    </el-form-item>

    <el-form-item>
      <router-link :to="{ path: 'cards/print', query: $route.query }" v-if="showPrintLink">
        <i class="fa fa-print"></i>
        Print view
      </router-link>
    </el-form-item>
  </el-form>
</template>

<script>
import { debounce } from 'lodash-es';
import { mapGetters, mapState } from 'vuex';
import DeckSelector from '../form/DeckSelector.vue';
import SetSelector from '../form/SetSelector.vue';
import ACheckbox from '../ui/ACheckbox.vue';
import AInput from '../ui/AInput.vue';

const debouncedChangeRoute = debounce(($router, query) => {
  $router.push({ query });
}, 250);

// Configuration for common query filter properties.
const filterConfig = [
  //{ name: 'brand' },
  { name: 'deck' },
  { name: 'set' },
  { name: 'cardId', debouncing: true },
  { name: 'name', debouncing: true },
  { name: 'text', debouncing: true },
  { name: 'translatedFirst' },
  { name: 'hideFullyTranslated' },
  { name: 'translationSuggestions' },
];
const x = {
  ...filterConfig
    /**
     * From a property configuration, generates a computed getter/setter pair, and returns an object with a single
     * property (the name), and puts the getter/setter as its value.
     */
    .map(({ name, debouncing }) => {
      const getterSetter = {
        get() {
          return this.filterData[name];
        },
        set(value) {
          console.info('this.filterData[name]', this.filterData[name]);

          this.filterData[name] = value;
          const query = { ...this.$route.query };
          delete query.page; // Always clear the page when the filters change.
          delete query[name];
          if (value) {
            query[name] = value;
          }

          if (debouncing) {
            debouncedChangeRoute(this.$router, query);
          } else {
            this.$router.push({ query });
          }
        },
      };
      return {
        [name]: getterSetter,
      };
    })
    /**
     * Now we want to take the array where each contains an object with a single key (property) with a getter/setter
     * in them, and make it into a single object containing all the properties with their getter/setters on them.
     */
    .reduce((acc, current) => {
      return { ...acc, ...current };
    }, {}),
};

/** @class CardFilters */
export default {
  name: 'CardFilters',
  components: { AInput, ACheckbox, SetSelector, DeckSelector },
  data() {
    return {
      filterData: {},
      isLocaleJapanese: window.locale === 'ja',
    };
  },
  computed: {
    ...mapState('cards', {
      totalCards: state => state.list.meta.total,
    }),
    ...mapGetters('sets', ['brands']),
    ...x,
    _brands() {
      if (!this.brands) {
        return null;
      }

      return [...this.brands].sort().map(brand => ({
        value: brand || '-1',
        label: brand || '(Unknown or no brand)',
      }));
    },
    showPrintLink() {
      return !this.isLocaleJapanese && 1 <= this.totalCards && this.totalCards <= 60;
    },
  },
  methods: {
    clearAllFilters() {
      const query = { ...this.$route.query };
      filterConfig.forEach(({ name }) => {
        this.filterData[name] = '';
        delete query[name];
      });
      delete query.page;
      this.$router.push({ query });
    },
  },
  watch: {
    '$route.query': {
      immediate: true,
      handler() {
        const filterData = {};
        for (let { name } of filterConfig) {
          filterData[name] = String(this.$route.query[name] || '');
        }

        this.filterData = filterData;
      },
    },
  },
};
</script>

<style scoped>
.card-id-input {
  width: 225px;
}
</style>
