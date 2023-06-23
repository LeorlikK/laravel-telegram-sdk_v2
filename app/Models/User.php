<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $tg_id
 * @property string|null $username
 * @property string $first_name
 * @property string $last_name
 * @property string $language
 * @property string|null $mail
 * @property string|null $number
 * @property string|null $edit
 * @property int|null $is_administrator
 * @property int|null $is_premium
 * @property int|null $is_blocked
 * @property mixed $password
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\State|null $state
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsPremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

//    public $timestamps = false;


    public function state():HasOne
    {
        return $this->hasOne(State::class, 'user_id', 'id');
    }

    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function reports():HasMany
    {
        return $this->hasMany(Feedback::class, 'user_id', 'id');
    }

    public function pays():HasMany
    {
        return $this->hasMany(Pay::class, 'user_id', 'id');
    }

    public function updateCache(User $newUserValue): void
    {
        Cache::put($this->tg_id, $newUserValue, now()->addMinutes(20));
    }

    public function updatePurchasedProducts()
    {
        $collect = collect();
        $purchasedProducts = User::with('pays.product.folders')->where('tg_id', $this->tg_id)->first();
        if ($purchasedProducts) {
            $purchasedProducts->each(function ($user) use ($collect) {
                if ($user->pays) {
                    $user->pays->each(function ($item) use ($collect) {
                        if ($item->product && $item->product->folders) {
                            $collect->add($item->product->folders->pluck('id'));
                        }
                    });
                }
            });
        }

        $purchasedProducts = $collect->flatten()->unique();
        $this->purchasedProducts = $purchasedProducts;
    }
}
