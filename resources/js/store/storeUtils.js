export const mapComputed = normalizeNamespace(function mapComputed(namespace, data) {
  var res = {};
  if (process.env.NODE_ENV !== 'production' && !isValidMap(data)) {
    console.error('[vuex] mapComputed: mapper parameter must be either an Array or an Object');
  }
  normalizeMap(data).forEach(function (ref) {
    var key = ref.key;
    var val = ref.val;
    var gettersVal = namespace + val;
    res[key] = function mappedGetter() {
      if (namespace && !getModuleByNamespace(this.$store, 'mapData', namespace)) {
        return;
      }
      var state = this.$store.state;
      var getters = this.$store.getters;
      if (gettersVal in getters) {
        return getters[gettersVal];
      }
      if (namespace) {
        var module = getModuleByNamespace(this.$store, 'mapData', namespace);
        state = module.context.state;
        getters = module.context.getters;
      }
      return typeof val === 'function' ? val.call(this, state, getters) : state[val];
    };
    res[key].vuex = true;
  });
  return res;
});

/**
 * Return a function expect two param contains namespace and map. it will normalize the namespace and then the param's function will handle the new namespace and the map.
 * @param {Function} fn
 * @return {Function}
 */
function normalizeNamespace(fn) {
  return function (namespace, map) {
    if (typeof namespace !== 'string') {
      map = namespace;
      namespace = '';
    } else if (namespace.charAt(namespace.length - 1) !== '/') {
      namespace += '/';
    }
    return fn(namespace, map);
  };
}

/**
 * Search a special module from store by namespace. if module not exist, print error message.
 * @param {Object} store
 * @param {String} helper
 * @param {String} namespace
 * @return {Object}
 */
function getModuleByNamespace(store, helper, namespace) {
  var module = store._modulesNamespaceMap[namespace];
  if (process.env.NODE_ENV !== 'production' && !module) {
    console.error('[vuex] module namespace not found in ' + helper + '(): ' + namespace);
  }
  return module;
}

/**
 * Normalize the map
 * normalizeMap([1, 2, 3]) => [ { key: 1, val: 1 }, { key: 2, val: 2 }, { key: 3, val: 3 } ]
 * normalizeMap({a: 1, b: 2, c: 3}) => [ { key: 'a', val: 1 }, { key: 'b', val: 2 }, { key: 'c', val: 3 } ]
 * @param {Array|Object} map
 * @return {Object}
 */
function normalizeMap(map) {
  return Array.isArray(map)
    ? map.map(function (key) {
        return { key: key, val: key };
      })
    : Object.keys(map).map(function (key) {
        return { key: key, val: map[key] };
      });
}

/**
 * Validate whether given map is valid or not
 * @param {*} map
 * @return {Boolean}
 */
function isValidMap(map) {
  return Array.isArray(map) || isObject(map);
}
