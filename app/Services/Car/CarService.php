<?php

namespace App\Services\Car;

use App\Repositories\Car\CarRepository;

class CarService
{
    protected $carRepository;

    public function __construct(CarRepository $carRepository) {
        $this->carRepository = $carRepository;
    }

    public function all()
    {
        return $this->carRepository->all();
    }

    public function create(array $data)
    {
        return $this->carRepository->create($data);
    }

    public function show($id)
    {
        return $this->carRepository->find($id);
    }

    public function update($id, array $data)
    {
        $car = $this->carRepository->find($id);
        return $this->carRepository->update($car, $data);
    }

    public function delete($id)
    {
        $car = $this->carRepository->find($id);
        return $this->carRepository->delete($car);
    }

    public function getCarsByUser($userId)
    {
        return $this->carRepository->getCarsByUser($userId);
    }
}