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

        // --- Dias da semana ---
        $weekDaysMap = [
            'Segunda-feira' => 0,
            'Terça-feira'   => 1,
            'Quarta-feira'  => 2,
            'Quinta-feira'  => 3,
            'Sexta-feira'   => 4,
            'Sábado'        => 5,
            'Domingo'       => 6,
        ];

        $requestedDays = $preferences['days'] ?? [];
        $rideDays = $ride->weekDays->pluck('day_of_week')->toArray() ?? [];

        // Converte para índices numéricos
        $requestedDaysNumeric = array_map(fn($d) => $weekDaysMap[$d] ?? null, $requestedDays);
        $requestedDaysNumeric = array_filter($requestedDaysNumeric, fn($d) => $d !== null);

        $rideDaysNumeric = array_map(fn($d) => $weekDaysMap[$d] ?? null, $rideDays);
        $rideDaysNumeric = array_filter($rideDaysNumeric, fn($d) => $d !== null);

        // Score de coincidência de dias
        $dayScore = count(array_intersect($requestedDaysNumeric, $rideDaysNumeric)) / max(count($requestedDaysNumeric), 1);

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
