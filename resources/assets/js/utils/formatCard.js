/**
 * Formats card text to use pretty icons and stuff.
 */
export default function(text = '') {
  return text.replace(/\[0]/g, '<span class="card-text-icon card-text-icon-free"></span>');
}
