import { reactive } from 'vue';

const temporaryInitial = { id: null, variant: null };

let _savedCardVariants;
try {
  _savedCardVariants = {
    preferences: JSON.parse(localStorage.getItem('cardVariants') ?? '{}'),
    temporary: temporaryInitial,
  };
} catch (e) {
  _savedCardVariants = {
    preferences: {},
    temporary: temporaryInitial,
  };
}

/**
 * User's stored card variant preferences.
 *
 * We need to wrap the preferences in an additional object ({preferences: {}}) so Vue could detect
 * property additions/deletions.
 */
export const savedCardVariants = reactive(_savedCardVariants);

/**
 * Updates the user's preference for a card and saves it to localStorage.
 * @param id
 * @param variant
 */
export function saveCardVariant(id, variant) {
  if (variant) {
    savedCardVariants.preferences[id] = variant;
  } else {
    delete savedCardVariants.preferences[id];
  }
  localStorage.setItem('cardVariants', JSON.stringify(savedCardVariants.preferences));
}

/** Set a variant for a card temporarily (hovering over the variant). */
export function setTemporaryVariant(id, variant) {
  savedCardVariants.temporary.id = id;
  savedCardVariants.temporary.variant = variant;
}

export function unsetTemporaryVariant() {
  savedCardVariants.temporary.id = null;
  savedCardVariants.temporary.variant = null;
}

/** Reactively returns the saved variant, falling back to the empty string variant. */
export function getSavedVariant(id) {
  return savedCardVariants.preferences[id] ?? '';
}

/**
 * Reactively returns the variant that should be used for the given card ID.
 * The current one is the temporary one for the ID if it exists, otherwise the saved one.
 **/
export function getCurrentVariant(id) {
  if (id === savedCardVariants.temporary.id) {
    return savedCardVariants.temporary.variant;
  }

  return getSavedVariant(id);
}
