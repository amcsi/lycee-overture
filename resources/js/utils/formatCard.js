import { escape } from './html';

/**
 * @param name
 * @param text The hidden text for when copy-pasting.
 * @param long {Boolean}
 * @return {string}
 */
function getIcon(name, text, long) {
  const clsExtra = long ? ' card-text-icon-long' : '';
  return `<span class="card-text-icon${clsExtra} card-text-icon-${name}">${text}</span>`;
}

function simpleCallback(match, contents) {
  return getIcon(contents, match);
}

function discardCallback(match, contents) {
  return getIcon('d' + contents, match);
}

function abilityTypeCallback(match, contents) {
  return getIcon(contents.toLowerCase(), match, true);
}

/**
 * Formats card text to use pretty icons and stuff.
 */
export default function(text = '') {
  text = text
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
  text = text.replace(/"(.*?)"/g, (_, name) => {
    // Get the same string, but it made into a link with a new name filter.

    const url = new URL(document.location);
    const params = url.searchParams;
    params.set('name', name);
    const href = url.toString();
    const safeName = escape(name);
    return `<a href="${href}" data-key="name" data-value="${safeName}">"${safeName}"</a>`;
  });
  text = text.replace(/\[T]/g, getIcon('tap'));
  text = text.replace(/\[(0|star|snow|moon|flower|space|sun)]/g, simpleCallback);
  text = text.replace(/\[(Activate|Trigger|Continuous)]/g, abilityTypeCallback);
  text = text.replace(/\[D([1-4])]/g, discardCallback);
  text = text.replace(/{(.*?)}/g, `<span class="target">$1</span>`);
  return text;
}
