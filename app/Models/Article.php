<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * amcsi\LyceeOverture\Article
 *
 * @property int $id
 * @property string $title
 * @property string $markdown
 * @property string $html
 * @property \Carbon\CarbonImmutable $updated_at
 * @property \Carbon\CarbonImmutable $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMarkdown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    //
}
