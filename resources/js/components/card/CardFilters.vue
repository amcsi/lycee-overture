<template>
    <el-form inline ref="form" label-width="120px">
        <el-form-item label="Set">
            <el-select placeholder="-" v-model="set">
                <el-option label="-" value=""></el-option>
                <el-option label="(Unknown or no set)" value="-1"></el-option>
                <el-option
                    v-for="item in setList"
                    :key="item.id"
                    :label="item.full_name"
                    :value="String(item.id)"
                ></el-option>
            </el-select>
        </el-form-item>

        <el-form-item label="Brand">
            <el-select placeholder="-" v-model="brand">
                <el-option label="-" value=""></el-option>
                <el-option
                    v-for="{value, label} in _brands"
                    :key="value"
                    :label="label"
                    :value="value"
                ></el-option>
            </el-select>
        </el-form-item>

        <el-form-item label="Starter deck">
            <el-select placeholder="-" v-model="deck">
                <el-option label="-" value=""></el-option>
                <el-option
                    v-for="cardSet in cardSetList"
                    :key="cardSet.id"
                    :label="cardSet.name"
                    :value="'' + cardSet.id"
                ></el-option>
            </el-select>
        </el-form-item>

        <el-form-item :label="$t('cardFilters.cardId')">
            <el-input class="card-id-input" placeholder="LO-0001,LO-0002" v-model="cardId" />
        </el-form-item>

        <el-form-item :label="$t('cardFilters.nameOfCard')">
            <el-input class="card-id-input" v-model="name" />
        </el-form-item>

        <el-form-item :label="$t('cardFilters.cardText')">
            <el-input class="card-id-input" v-model="text" />
        </el-form-item>

        <div style="clear: both;"></div>

        <el-form-item>
            <el-checkbox v-model="translatedFirst" :label="$t('cardFilters.translatedFirst')" />
        </el-form-item>

        <el-form-item>
            <a href="#" @click.prevent="clearAllFilters"><i class="fa fa-eraser"></i> Clear all
                filters</a>
        </el-form-item>

        <router-link :to="{path: 'cards/print', query: $route.query }" v-if="1 <= totalCards && totalCards <= 60">
            <i class="fa fa-print"></i>
            Print view
        </router-link>
    </el-form>
</template>

<script>
import debounce from 'lodash.debounce';
import { mapGetters, mapState } from 'vuex';

const debouncedChangeRoute = debounce(($router, query) => {
  $router.push({ query });
}, 250);

  // Configuration for common query filter properties.
  const filterConfig = [
    { name: 'brand' },
    { name: 'deck' },
    { name: 'set' },
    { name: 'cardId', debouncing: true },
    { name: 'name', debouncing: true },
    { name: 'text', debouncing: true },
  ];
  /** @class CardFilters */
  export default {
    name: 'CardFilters',
    data() {
      return {
        filterData: {},
      }
    },
    computed: {
      ...mapState('cardSets', {
        cardSetList: 'list',
      }),
      ...mapState('sets', {
        setList: 'list',
      }),
      ...mapState('cards', {
        totalCards: state => state.list.meta.pagination.total,
      }),
      ...mapGetters('sets', ['brands']),
      ...(filterConfig)
      /**
       * From a property configuration, generates a computed getter/setter pair, and returns an object with a single
       * property (the name), and puts the getter/setter as its value.
       */
        .map(
          ({ name, debouncing }) => {
            const getterSetter = {
              get() {
                return this.filterData[name];
              },
              set(value) {
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
      translatedFirst: {
        get() {
          return !!this.$route.query.translatedFirst;
        },
        set(translatedFirst) {
          const query = { ...this.$route.query };
          delete query.translatedFirst;
          if (translatedFirst) {
            query.translatedFirst = 1;
          }
          this.$router.push({ query });
        },
      },
      _brands() {
        if (!this.brands) {
          return null;
        }

        return [...this.brands].sort().map(brand => ({
          value: brand || '-1',
          label: brand || '(Unknown or no brand)',
        }));
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
      $route: {
        immediate: true,
        handler() {
          const filterData = {};
          for (let { name } of filterConfig) {
            filterData[name] = this.$route.query[name] || '';
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
