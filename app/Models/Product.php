<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
    ];

    protected function importedT(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->toIso8601String(),
            set: fn($value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
