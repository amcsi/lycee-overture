/**
 * @param name
 * @param text The hidden text for when copy-pasting.
 * @return {string}
 */
function getIcon(name, text) {
  return `<span class="card-text-icon card-text-icon-${name}">${text}</span>`;
}

/**
 * Formats card text to use pretty icons and stuff.
 */
export default function(text = '') {
  text = text.replace(/\[T]/g, getIcon('tap'));
  text = text.replace(/\[(0|star|snow|moon|flower|space|sun)]/g, (match, contents) => {
    return getIcon(contents, match);
  });
  return text;
}
