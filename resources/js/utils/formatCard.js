import { escape } from './html';

//noinspection NonAsciiCharacters
const japaneseIconMap = {
  '無': 'star',
  '雪': 'snow',
  '月': 'moon',
  '花': 'flower',
  '宙': 'space',
  '日': 'sun',
};

//noinspection NonAsciiCharacters
const japaneseAbilityTypeMap = {
  '宣言': 'Activate',
  '誘発': 'Trigger',
  '常時': 'Continuous',
  'コスト': 'Cost',
  '装備制限': 'Equip Restriction',
  '手札宣言': 'Hand Activate',
};

const abbreviations = {
  'Hand Activate': 'Hand Act.',
};

const japaneseAbilityTypeRegex = /\[(宣言|誘発|常時|コスト|手札宣言)]/g;

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

/**
 * Multiple elements next to each other are all enclosed with a single pair of square brackets.
 * For copy-ability, they need to be enclosed with invisible copiable brackets.
 */
function japaneseElementsCallback(match, contents) {
  const iconsString = contents.split('').map(japaneseElementChar => getIcon(
    japaneseElementChar === 'T' ? 'tap' : japaneseIconMap[japaneseElementChar],
    japaneseElementChar,
  )).join('');

  return `<span class=ict>[</span>${iconsString}<span class="ict">]</span>`;
}

function discardCallback(match, contents) {
  return getIcon('d' + contents, match);
}

function abilityTypeCallback(match, contents) {
  const englishAbilityType = japaneseAbilityTypeMap[contents] || contents;
  const cls = englishAbilityType.toLowerCase().replace(' ', '-');
  const text = abbreviations[contents] || contents;
  return (
    `<span class="ict">[</span><span class="card-ability-type-${cls}">${text}</span><span class="ict">]</span>`
  );
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
  text = text.replace(/\[([T無雪月花宙日]+)]/g, japaneseElementsCallback);
  text = text.replace(/\[(Activate|Trigger|Continuous|Cost|Hand Activate)]/g, abilityTypeCallback);
  text = text.replace(japaneseAbilityTypeRegex, abilityTypeCallback);
  text = text.replace(/\[D([1-4])]/g, discardCallback);
  text = text.replace(/{(.*?)}/g, `<span class="target">$1</span>`);
  text = text.replace(/\n/g, '<br>');
  return text;
}
