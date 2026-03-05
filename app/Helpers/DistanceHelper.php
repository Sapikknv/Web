<?php

namespace App\Helpers;

class DistanceHelper
{
    /**
     * Calculate the distance between two points on Earth using the Haversine formula.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @param string $unit ('km' or 'mi')
     * @return float
     */
    public static function haversine($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        $earthRadius = ($unit === 'km') ? 6371 : 3959;

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
