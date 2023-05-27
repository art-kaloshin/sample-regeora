<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $first_name
 * @property string $last_name
 * @property Carbon $birthday
 * @property int $id
 */
class Patient extends Model
{
    use HasFactory;

    const AGE_IN_DAYS = 1;
    const AGE_IN_MONTH = 2;
    const AGE_IN_YEARS = 3;
    const AGE_STRING = [
        1 => 'день',
        2 => 'месяц',
        3 => 'лет'
    ];

    protected function birthday(): Attribute
    {
        return Attribute::make(
            function ($value) {
                return Carbon::make($value);
            },
            function (string $value, array $attributes) {
            $birthday = Carbon::make($value);
            $attributes['birthday'] = $birthday;

            $diffDays = Carbon::now()->diffInDays($birthday);
            if ($diffDays <= 30) {
                $attributes['age'] = $diffDays;
                $attributes['age_type'] = self::AGE_IN_DAYS;

                return $attributes;
            }

            $diffMonth = Carbon::now()->diffInMonths($birthday);
            if ($diffMonth <= 12) {
                $attributes['age'] = $diffMonth;
                $attributes['age_type'] = self::AGE_IN_MONTH;

                return $attributes;
            }

            $diffYears = Carbon::now()->diffInYears($birthday);
            $attributes['age'] = $diffYears;
            $attributes['age_type'] = self::AGE_IN_YEARS;

            return $attributes;
        });
    }
}
