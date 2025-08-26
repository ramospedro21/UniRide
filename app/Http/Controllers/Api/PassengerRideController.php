<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Services\Notifications\PushNotificationsService;
use App\Services\PassengerRide\PassengerRideService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PassengerRideController extends Controller
{
    protected $passengerRideService;

    public function __construct(PassengerRideService $passengerRideService) {
        $this->passengerRideService = $passengerRideService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'ride_id' => [
                    'required',
                    'integer',
                    Rule::unique('passenger_rides')->where(function ($query) use ($request) {
                        return $query->where('user_id', $request->user_id);
                    }),
                ],
                'user_id' => 'required|integer',
            ], [
                'ride_id.unique' => 'Você já reservou essa carona.',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();

            $this->passengerRideService->create($data);

            $ride = Ride::find($data['ride_id']);
            $driver = User::find($ride->driver_id);
            $passenger = User::find($data['user_id']);

            if ($driver && $driver->device_token) {
                PushNotificationsService::sendNotification(
                    $driver->device_token,
                    "Nova reserva na sua carona 🚗",
                    "{$passenger->name} acabou de reservar um assento na sua carona!",
                    [
                        'ride_id' => $ride->id,
                        'passenger_id' => $passenger->id
                    ]
                );
            }

            DB::commit();

            return $this->respondWithOk();

        } catch (ValidationException $e) {
            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);

        } catch (\Exception $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function handleReservation(Request $request, int $passenger_ride_id)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'action' => [
                    'required',
                    Rule::in(['approve', 'reject']),
                ],
                'ride_id' => 'required|integer',
                'passenger_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();

            $this->passengerRideService->handleReservation($passenger_ride_id, $data['action']);

            $ride = Ride::find($data['ride_id']);
            $driver = User::find($ride->driver_id);
            $passenger = User::find($data['passenger_id']);

            if ($passenger && $passenger->device_token) {
                if($data['action'] === 'approve') {
                    $headMessage = "Sua reserva na carona de {$driver->name} foi aprovada! 🚗";
                    $bodyMessage = "Prepare-se para a viagem!";
                } else {
                    $headMessage = "Sua reserva na carona de {$driver->name} foi rejeitada. 😞";
                    $bodyMessage = "Infelizmente, sua reserva não foi aprovada.";
                }

                PushNotificationsService::sendNotification(
                    $passenger->device_token,
                    $headMessage,
                    $bodyMessage,
                    [
                        'ride_id' => $ride->id,
                        'passenger_id' => $passenger->id
                    ]
                );
            }

            DB::commit();

            return $this->respondWithOk();

        } catch (ValidationException $e) {
            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);

        } catch (\Exception $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}
