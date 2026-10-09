<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image;

/**
 * The image variants the app serves. Values are the Cloudinary transformation strings that produced them, so they must
 * not be reordered or reformatted: Cloudinary treats any change as a new derived image.
 */
enum ImageFormat: string
{
    case Full = 'q_auto,f_auto';
    case Height150 = 'q_auto,f_auto,h_150';
    case Height300 = 'q_auto,f_auto,h_300';
    case Height520 = 'q_auto,f_auto,h_520';
    case Width281 = 'w_281';
}
