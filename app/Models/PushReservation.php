<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushReservation extends Model
{
    use HasFactory;

    public static function getOneTimeMessage()
    {
        $now = self::generateTenMinutesTime('Y-m-d H:i');

        return self::where(['date', $now])->get();
    }

    public static function getWeeklyMessage()
    {
        $todayDayName = now()->locale('ko')->getTranslatedShortDayName();
        $now = self::generateTenMinutesTime('H:i');

        return self::where([
            ['weekday', 'LIKE', "%$todayDayName%"],
            ['time', '=', $now]
        ])->get();
    }

    public static function generateTenMinutesTime($format)
    {
        return substr(now()->format($format), 0, -1) . '0';
    }
}
