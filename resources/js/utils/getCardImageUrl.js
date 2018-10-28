export default function getCardImageUrl(id, cloudinaryHeight, extension = 'jpg') {
  const modifiers = [];
  if (cloudinaryHeight) {
    modifiers.push(`h_${cloudinaryHeight}`);
  }
  const urlModifierPart = modifiers.length ? '/' + modifiers.join(',') : '';
  return `https://res.cloudinary.com/${window.vars.cloudinaryCloudName}/image/upload${urlModifierPart}/cards/${id}.${extension}`;
}
