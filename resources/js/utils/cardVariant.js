import Vue from 'vue';

let _savedCardVariants;
try {
  _savedCardVariants = { preferences: JSON.parse(localStorage.getItem('cardVariants') ?? '{}') };
} catch (e) {
  _savedCardVariants = { preferences: {} };
}
/**
 * User's stored card variant preferences.
 *
 * We need to wrap the preferences in an additional object ({preferences: {}}) so Vue could detect
 * property additions/deletions.
 */
export const savedCardVariants = Vue.observable(_savedCardVariants);
console.info({ savedCardVariants });

/**
 * Updates the user's preference for a card and saves it to localStorage.
 * @param id
 * @param variant
 */
export function saveCardVariant(id, variant) {
  if (variant) {
    Vue.set(savedCardVariants.preferences, id, variant);
  } else {
    Vue.delete(savedCardVariants.preferences, id);
  }
  localStorage.setItem('cardVariants', JSON.stringify(savedCardVariants.preferences));
}
