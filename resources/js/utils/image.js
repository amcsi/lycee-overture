/**
 * Mirrors the case names of the backend's ImageFormat enum, which the URL templates in window.vars are keyed by.
 */
export const ImageFormat = Object.freeze({
  Full: 'Full',
  Height150: 'Height150',
  Height300: 'Height300',
  Height520: 'Height520',
  Width281: 'Width281',
});

/**
 * @param {string} path Source image path without an extension, e.g. "cards/LO-0001".
 * @param {string} format One of ImageFormat.
 */
export function imageUrl(path, format = ImageFormat.Full) {
  const template = window.vars.imageUrlTemplates[format];
  if (!template) {
    throw new Error(`Unknown image format: ${format}`);
  }
  return template.replace('{path}', path);
}

export function cardImageUrl(cardId, format = ImageFormat.Full) {
  return imageUrl(`cards/${cardId}`, format);
}
