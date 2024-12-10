<?php

namespace App\Models;

use App\Enums\CurrencyTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'bio',
        'hourly_rate',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'currency' => CurrencyTypeEnum::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name.' '.$this->last_name
        );
    }

    public function convertedRate(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->converted_rate ?? $this->hourly_rate),
            set: fn ($value) => $this->converted_rate = $value
        );
    }

    public function convertedCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->converted_currency ?? $this->currency,
            set: fn ($value) => $this->converted_currency = $value
        );
    }

    public function isConverted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->converted_currency !== $this->currency
        );
    }

    public static function findByUuid($uuid)
    {
        return static::where('uuid', '=', $uuid)->first();
    }
}
