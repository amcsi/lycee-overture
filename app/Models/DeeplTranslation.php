<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * amcsi\LyceeOverture\DeeplTranslation
 *
 * @property int $id
 * @property string $source
 * @property string $translation
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property string $last_used_at
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation whereTranslation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeeplTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeeplTranslation extends Model
{

}
