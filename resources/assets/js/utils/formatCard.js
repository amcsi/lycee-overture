function getIcon(name) {
  return `<span class="card-text-icon card-text-icon-${name}"></span>`;
}

/**
 * Formats card text to use pretty icons and stuff.
 */
export default function(text = '') {
  text = text.replace(/\[T]/g, getIcon('tap'));
  text = text.replace(/\[(0|star|snow|moon|flower|space|sun)]/g, (match, contents) => {
    return getIcon(contents);
  });
  return text;
}
