<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Validation\ValidationException;

class ClientSearchService
{

    /**
     * Ügyfelek keresése név vagy okmányazonosító alapján.
     *
     * Ha csak okmányazonosító van megadva, akkor pontos egyezéssel keres.
     * Ha név alapján történik a keresés, akkor részleges egyezést vizsgál.
     * Ha a név alapján több mint egy találat van, hibát dob a pontosítás szükségessége miatt.
     * Ha egy találat van, lekéri az ügyfélhez tartozó autók és szerviznaplók számát is.
     *
     * @param string|null $name Az ügyfél neve (részleges kereséshez)
     * @param string|null $cardNumber Az okmányazonosító (pontos kereséshez)
     * @return \Illuminate\Support\Collection A találatok listája (Client modellek gyűjteménye)
     *
     * @throws \Illuminate\Validation\ValidationException Ha név alapján túl sok találat van
     */
    public function searchClients(?string $name, ?string $cardNumber)
    {
        $query = Client::query();

        if ($cardNumber) {
            $query->where('card_number', $cardNumber); // Pontos egyezés
        } elseif ($name) {
            $query->where('name', 'LIKE', "%{$name}%"); // Részleges egyezés
        }

        $clients = $query->get();

        // Ha név alapján több mint 1 találat van, hibát dobunk
        if ($name && $clients->count() > 1) {
            throw ValidationException::withMessages([
                'name' => ['Túl sok találat! Kérem, pontosítsa az ügyfél nevét.']
            ]);
        }

        if ($clients->count() === 1) {
            foreach ($clients as $client) {
                $client->car_count = $client->cars->count(); // Autók száma
                $client->service_log_count = Client::getServiceLogCountByClientId($client->id); // Szerviznaplók száma
            }
        }

        return $clients;
    }
}
