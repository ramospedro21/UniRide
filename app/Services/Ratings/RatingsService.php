<?php

namespace App\Services\Ratings;

use App\Repositories\Ratings\RatingsRepository;

class RatingsService
{
    protected $ratingsRepository;

    public function __construct(RatingsRepository $ratingsRepository) {
        $this->ratingsRepository = $ratingsRepository;
    }

    public function all()
    {
        return $this->ratingsRepository->all();
    }

    public function create(array $data)
    {
        return $this->ratingsRepository->create($data);
    }

    public function show($id)
    {
        return $this->ratingsRepository->find($id);
    }

    public function update($id, array $data)
    {
        $ratings = $this->ratingsRepository->find($id);
        return $this->ratingsRepository->update($ratings, $data);
    }

    public function delete($id)
    {
        $ratings = $this->ratingsRepository->find($id);
        return $this->ratingsRepository->delete($ratings);
    }
}