<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pushTestUser()
    {
        return $this->hasOne(TestPushUser::class);
    }

    public static function getAllowPushMessage()
    {
        return DB::table('users')
            ->select('device_key')
            ->where([
                ['send_marketing_push',  1],
                ['send_push_message', 1]
            ])
            ->whereNotNull('device_key')
            ->groupBy('device_key')
            ->get()
            ->pluck('device_key')
            ->toArray();
    }

    public static function getTestUser()
    {
        return self::whereHas('pushTestUser')
            ->whereNotNull('device_key')
            ->where([
                ['send_marketing_push',  1],
                ['send_push_message', 1]
            ])
            ->get()
            ->pluck('device_key')
            ->toArray();
    }
}
