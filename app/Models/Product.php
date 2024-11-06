<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    protected $fillable = [
        'code'
    ];

    protected function importedT(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->toIso8601String(),
            set: fn($value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }
}
