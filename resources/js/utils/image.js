export function assembleCloudinaryImageUrl(imageName, options = {}, modifiers = []) {
  modifiers.push('q_auto');
  modifiers.push('f_auto');
  if (options.cloudinaryHeight) {
    modifiers.push(`h_${options.cloudinaryHeight}`);
  }
  return `https://res.cloudinary.com/${window.vars.cloudinaryCloudName}/image/upload/${modifiers.join(
    ',')}/cards/${imageName}.jpg`;
}
