/**
 * @param name
 * @param text The hidden text for when copy-pasting.
 * @return {string}
 */
function getIcon(name, text) {
  return `<span class="card-text-icon card-text-icon-${name}">${text}</span>`;
}

function simpleCallback(match, contents) {
  return getIcon(contents, match);
}

function discardCallback(match, contents) {
  return getIcon('d' + contents, match);
}

/**
 * Formats card text to use pretty icons and stuff.
 */
export default function(text = '') {
  text = text.replace(/\[T]/g, getIcon('tap'));
  text = text.replace(/\[(0|star|snow|moon|flower|space|sun)]/g, simpleCallback);
  text = text.replace(/\[D([1-4])]/g, discardCallback);
  return text;
}
