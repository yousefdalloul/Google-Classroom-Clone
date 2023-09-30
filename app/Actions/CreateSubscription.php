<?php
namespace App\Actions;

use App\Models\Subscription;

class CreateSubscription
{
    public function create(array $data):Subscription
    {
        return Subscription::create($data);
    }
}