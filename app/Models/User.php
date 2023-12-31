<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;


/**
 * App\Models\User
 *
 * @property mixed $password
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pay> $pays
 * @property-read int|null $pays_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reportsFrom
 * @property-read int|null $reports_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reportsWhom
 * @property-read int|null $reports_whom_count
 * @property-read \App\Models\Role|null $role
 * @property-read \App\Models\State|null $state
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
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

    public function pays():HasMany
    {
        return $this->hasMany(Pay::class, 'user_id', 'id');
    }

    public function reportsFrom():HasMany
    {
        return $this->hasMany(Report::class, 'from', 'id');
    }

    public function reportsWhom():HasMany
    {
        return $this->hasMany(Report::class, 'to_whom', 'id');
    }

    public function updateCache(User $newUserValue): void
    {
        Cache::put($this->tg_id, $newUserValue, now()->addMinutes(20));
    }

    public function updatePurchasedProducts(): void
    {
        $collect = collect();
        $this->load('pays.product.folders');
        if (!$this->pays->isEmpty()) {
            $this->pays->each(function ($item) use ($collect) {
                if ($item->product && $item->product->folders && ($item->subscription > now() || $item->subscription === null)) {
                    $collect->add($item->product->folders->pluck('id'));
                }
            });
        }
        $this->unsetRelation('pays');

        $purchasedProducts = $collect->flatten()->unique();
        $this->purchasedProducts = $purchasedProducts;
    }

    public function countAnswerReportState(string $type): string
    {
        $numberArray = [
            '1' => "1️⃣",
            '2' => "2️⃣",
            '3' => "3️⃣",
            '4' => "4️⃣",
            '5' => "5️⃣",
            '6' => "6️⃣",
            '7' => "7️⃣",
            '8' => "8️⃣",
            '9' => "9️⃣➕",
        ];

        if ($type === 'answer'){
            $reports = Report::where('to_whom', $this->id)
                ->where('type', $type)
                ->where('state', false)
                ->get();
        }elseif ($type === 'report'){
            $reports = Report::where('type', $type)
                ->where('state', false)
                ->get();
        }else return "";


        $count = $reports->count();
        if ($count == 0) return "";
        elseif ($count > 0 && $count < 10) return $numberArray[$count];
        else return $numberArray[9];
    }
}
