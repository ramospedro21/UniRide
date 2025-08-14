<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Car\CarService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CarController extends Controller
{
    protected $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function index()
    {
        $cars = $this->carService->all();

        return $this->respondWithOk($cars);
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                "driver_id" => "required|integer",
                "brand" => "required|string",
                "model" => "required|string",
                "plate" => "required|string",
                "color" => "required|string",
                "is_default_veichle" => "required|boolean",
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();
    
            $this->carService->create($data);
    
            return $this->respondWithOk([], 201);
        } catch (ValidationException $e) {

            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);

        } catch (\Exception $e) {

            return $this->respondWithErrors($e->getMessage());

        }
    }

    public function show($id)
    {
        $car = $this->carService->show($id);
        
        return $this->respondWithOk($car);
    }

    public function update(Request $request, $id)
    {
        $this->carService->update($id, $request->toArray());

        return $this->respondWithOk();
    }

    public function delete($id)
    {
        $this->carService->delete($id);

        return $this->respondWithOk();
    }

    public function getCarsByUser($userId)
    {
        $cars = $this->carService->getCarsByUser($userId);

        return $this->respondWithOk($cars);
    }
}
