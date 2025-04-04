<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'car_id', 'type', 'registered', 'ownbrand', 'accidents'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Lekérdezi az adott autóhoz tartozó legutóbbi szervizbejegyzést.
     * 
     * Opcionálisan szűrhető ügyfél- és autóazonosító alapján.
     * Az eredmény a legfrissebb log_number értékű rekord lesz
     *
     * @param int|null $clientId Az ügyfél azonosítója (opcionális)
     * @param int|null $carId Az autó azonosítója (opcionális)
     * @return \Illuminate\Database\Eloquent\Relations\HasOne Az Eloquent kapcsolat objektuma
     */
    public function latestService($clientId = null, $carId = null)
    {
        $query = $this->hasOne(Service::class, 'car_id', 'car_id')
            ->orderByDesc('log_number')
            ->limit(1);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($carId) {
            $query->where('car_id', $carId);
        }

        return $query;
    }
}