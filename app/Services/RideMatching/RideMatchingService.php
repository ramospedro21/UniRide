<?php

namespace App\Services\RideMatching;

use App\Models\Ride;
use Carbon\Carbon;

class RideMatchingService
{
    protected array $weights = [
        'departure_distance' => 0.5,
        'driver_rating' => 0.1,
        'day_of_week' => 0.3,
    ];

    /**
    * Calcula a pontuação da carona considerando distância até a origem, avaliação do motorista e coincidência de dias.
    */
    public function calculateScore(Ride $ride, array $preferences): float
    {
        // --- Distância ---
        $departureDistance = (float) ($ride->departure_distance ?? 9999);
        $depNorm = $this->normalizeDistance($departureDistance); // já normaliza para 0-1, 1 = melhor

        // --- Rating do motorista ---
        $rating = $ride->driver ? $ride->driver->averageRating() : 0;
        $ratingNorm = $this->normalizeRating($rating); // já normaliza para 0-1

        $requestedDays = $preferences['days'] ?? [];
        $rideDays = $ride->weekDays->pluck('day_of_week')->toArray() ?? [];

        $dayScore = count(array_intersect($requestedDays, $rideDays)) / max(count($requestedDays), 1);

        // --- Score final com heurística multicritério ---
        $score = (
            $depNorm * $this->weights['departure_distance'] + // maior prioridade
            $dayScore * $this->weights['day_of_week'] +
            $ratingNorm * $this->weights['driver_rating']   // menor prioridade
        );

        return round($score, 4);
    }


    /** Normaliza distância (km) para 0..1. Quanto menor, melhor. */
    protected function normalizeDistance(float $km): float
    {
        $max = 10;
        $val = max(0, ($max - $km) / $max);
        return round(min($val, 1), 4);
    }

    /** Normaliza avaliação (0..5) para 0..1. Quanto maior, melhor. */
    protected function normalizeRating(float $rating): float
    {
        return round($rating / 5, 4);
    }
}
