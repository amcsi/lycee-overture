<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * amcsi\LyceeOverture\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $can_approve_locale
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereCanApproveLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
