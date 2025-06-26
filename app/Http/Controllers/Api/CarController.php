<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Car\CarService;
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
        $data = $request->validate([
            "driver_id" => "required|integer",
            "brand" => "required|string",
            "model" => "required|string",
            "color" => "required|string",
            "plate" => "required|string",
            "is_default_veichle" => "required|boolean",
        ]);

        $this->carService->create($data);

        return $this->respondWithOk([], 201);
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
}
