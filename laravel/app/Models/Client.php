<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'card_number'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public static function getServiceLogCountByClientId(int $clientId): int
    {
        return \DB::table('services')
            ->where('client_id', $clientId)
            ->count();
    }

    public function services()
    {
        return $this->hasManyThrough(Service::class, Car::class);
    }
}
