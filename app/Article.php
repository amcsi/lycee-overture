<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;

/**
 * amcsi\LyceeOverture\Article
 *
 * @property int $id
 * @property string $title
 * @property string $markdown
 * @property string $html
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article whereHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article whereMarkdown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    //
}
