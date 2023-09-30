<?php
namespace App\Concerns;


use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasPrice
{
    public function Price(): Attribute
    {
        return new Attribute(
            get: fn($price) => $price / 100,
            set: fn($price) => $price * 100,
        );
    }
}