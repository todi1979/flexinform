<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientSearchRequest;
use App\Services\ClientSearchService;
use Illuminate\Http\JsonResponse;

use App\Models\Car;
use App\Models\Service;

use Carbon\Carbon;

class CarserviceController extends Controller
{

    protected ClientSearchService $clientSearchService;

    public function __construct(ClientSearchService $clientSearchService)
    {
        $this->clientSearchService = $clientSearchService;
    }

    /**
     * Autószerviz főoldal megjelenítése.
     *
     * @return \Illuminate\View\View
     */
    public function indexView()
    {
        return view('carservice.index');
    }

    /**
     * Ügyfelek keresése név vagy okmányazonosító alapján.
     *
     * @param ClientSearchRequest $request A validált keresési adatok
     * @return JsonResponse Találatok JSON válaszként
     */
    public function index(ClientSearchRequest $request): JsonResponse
    {
        $clients = $this->clientSearchService->searchClients(
            $request->name,
            $request->card_number
        );

        return response()->json($clients);
    }

    /**
     * Az adott ügyfélhez tartozó autók lekérdezése és a legutóbbi szervizinformációk csatolása.
     *
     * @param int $clientId Az ügyfél azonosítója
     * @return JsonResponse Az autók listája JSON válaszként
     */
    public function getClientCars($clientId)
    {
        $cars = Car::where('client_id', $clientId)
            ->with(['latestService' => function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            }])
            ->get()
            ->map(function ($car) use ($clientId) {
                $latestService = $car->latestService($clientId, $car->car_id)->first();

                return [
                    'car_id'            => $car->car_id,
                    'client_id'         => $car->client_id,
                    'type'              => $car->type,
                    'registered'        => $car->registered
                        ? Carbon::parse($car->registered)->format('Y-m-d H:i:s')
                        : 'N/A',
                    'ownbrand'          => $car->ownbrand ? 'Igen' : 'Nem',
                    'accidents'         => $car->accidents,
                    'latest_event'      => optional($latestService)->event ?? 'N/A',
                    'latest_event_time' => optional($latestService)->event_time
                        ? Carbon::parse($latestService->event_time)->format('Y-m-d H:i:s')
                        : 'N/A',
                ];
            });

        return response()->json($cars);
    }

    /**
     * Egy adott autó szerviznaplójának lekérdezése.
     * Ha az esemény típusa "regisztralt", akkor a regisztráció ideje kerül visszaadásra.
     *
     * @param int $clientId Az ügyfél azonosítója
     * @param int $carId Az autó azonosítója
     * @return JsonResponse A szerviznapló adatai JSON válaszként
     */
    public function getServiceLog($clientId, $carId)
    {
        // Lekérdezzük a szerviznaplót, és ha az esemény 'regisztralt', akkor a regisztráció időpontját mutatjuk.
        $logs = Service::where('services.car_id', $carId)
        ->where('services.client_id', $clientId)
        ->join('cars', function ($join) {
            $join->on('cars.car_id', '=', 'services.car_id')
                 ->on('cars.client_id', '=', 'services.client_id');
        })
        ->get(['services.car_id', 'services.client_id', 'services.log_number', 'services.event', 'services.event_time', 'services.document_id', 'cars.registered as registration_time'])
        ->map(function ($log) {
            if ($log->event === 'regisztralt' && is_null($log->event_time)) {
                $log->event_time = $log->registration_time;
            }
            return $log;
        });

        return response()->json($logs);
    }
}

