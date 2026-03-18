<?php

namespace App\Services;

use App\Models\Equipment;
use Carbon\Carbon;

class DepreciationService
{
    /**
     * Calculate depreciation for an equipment
     */
    public function calculate(Equipment $equipment): array
    {
        if (!$equipment->purchase_price || !$equipment->purchase_date) {
            return [
                'method' => null,
                'purchase_price' => 0,
                'salvage_value' => 0,
                'depreciable_amount' => 0,
                'useful_life_years' => 0,
                'years_used' => 0,
                'annual_depreciation' => 0,
                'accumulated_depreciation' => 0,
                'current_value' => 0,
                'depreciation_rate' => 0,
                'is_fully_depreciated' => false,
            ];
        }

        $purchasePrice = (float) $equipment->purchase_price;
        $salvageValue = (float) $equipment->salvage_value;
        $usefulLife = (int) $equipment->useful_life_years;
        $purchaseDate = Carbon::parse($equipment->purchase_date);
        $now = now();

        $depreciableAmount = $purchasePrice - $salvageValue;
        $yearsUsed = $purchaseDate->diffInYears($now);

        // Cap years used at useful life
        $effectiveYearsUsed = min($yearsUsed, $usefulLife);

        $result = [
            'method' => $equipment->depreciation_method,
            'purchase_price' => $purchasePrice,
            'salvage_value' => $salvageValue,
            'depreciable_amount' => $depreciableAmount,
            'useful_life_years' => $usefulLife,
            'years_used' => $yearsUsed,
        ];

        if ($equipment->depreciation_method === 'declining_balance') {
            $result = array_merge($result, $this->calculateDecliningBalance(
                $purchasePrice,
                $salvageValue,
                $usefulLife,
                $effectiveYearsUsed
            ));
        } else {
            $result = array_merge($result, $this->calculateStraightLine(
                $purchasePrice,
                $salvageValue,
                $usefulLife,
                $effectiveYearsUsed
            ));
        }

        $result['is_fully_depreciated'] = $result['current_value'] <= $salvageValue;

        return $result;
    }

    /**
     * Straight-line depreciation
     */
    private function calculateStraightLine(
        float $purchasePrice,
        float $salvageValue,
        int $usefulLife,
        int $yearsUsed
    ): array {
        $depreciableAmount = $purchasePrice - $salvageValue;
        $annualDepreciation = $usefulLife > 0 ? $depreciableAmount / $usefulLife : 0;
        $accumulatedDepreciation = $annualDepreciation * $yearsUsed;
        $currentValue = max($purchasePrice - $accumulatedDepreciation, $salvageValue);
        $depreciationRate = $purchasePrice > 0 ? ($annualDepreciation / $purchasePrice) * 100 : 0;

        return [
            'annual_depreciation' => round($annualDepreciation, 2),
            'accumulated_depreciation' => round($accumulatedDepreciation, 2),
            'current_value' => round($currentValue, 2),
            'depreciation_rate' => round($depreciationRate, 2),
        ];
    }

    /**
     * Declining balance depreciation (double declining)
     */
    private function calculateDecliningBalance(
        float $purchasePrice,
        float $salvageValue,
        int $usefulLife,
        int $yearsUsed
    ): array {
        $rate = $usefulLife > 0 ? (2 / $usefulLife) : 0;
        $currentValue = $purchasePrice;
        $totalDepreciation = 0;
        $annualDepreciation = 0;

        for ($year = 1; $year <= $yearsUsed; $year++) {
            $annualDepreciation = $currentValue * $rate;

            // Don't depreciate below salvage value
            if ($currentValue - $annualDepreciation < $salvageValue) {
                $annualDepreciation = $currentValue - $salvageValue;
            }

            $totalDepreciation += $annualDepreciation;
            $currentValue -= $annualDepreciation;
        }

        return [
            'annual_depreciation' => round($annualDepreciation, 2),
            'accumulated_depreciation' => round($totalDepreciation, 2),
            'current_value' => round(max($currentValue, $salvageValue), 2),
            'depreciation_rate' => round($rate * 100, 2),
        ];
    }

    /**
     * Get depreciation schedule for equipment
     */
    public function getSchedule(Equipment $equipment): array
    {
        if (!$equipment->purchase_price || !$equipment->purchase_date) {
            return [];
        }

        $purchasePrice = (float) $equipment->purchase_price;
        $salvageValue = (float) $equipment->salvage_value;
        $usefulLife = (int) $equipment->useful_life_years;
        $purchaseDate = Carbon::parse($equipment->purchase_date);

        $schedule = [];
        $bookValue = $purchasePrice;
        $rate = $usefulLife > 0 ? (2 / $usefulLife) : 0;
        $straightLineDepreciation = $usefulLife > 0 ? ($purchasePrice - $salvageValue) / $usefulLife : 0;

        for ($year = 1; $year <= $usefulLife; $year++) {
            $yearDate = $purchaseDate->copy()->addYears($year - 1);

            if ($equipment->depreciation_method === 'declining_balance') {
                $depreciation = $bookValue * $rate;
                if ($bookValue - $depreciation < $salvageValue) {
                    $depreciation = $bookValue - $salvageValue;
                }
            } else {
                $depreciation = $straightLineDepreciation;
                if ($bookValue - $depreciation < $salvageValue) {
                    $depreciation = $bookValue - $salvageValue;
                }
            }

            $accumulatedDepreciation = $purchasePrice - $bookValue + $depreciation;
            $bookValue -= $depreciation;

            $schedule[] = [
                'year' => $year,
                'date' => $yearDate->format('Y'),
                'depreciation' => round($depreciation, 2),
                'accumulated_depreciation' => round($accumulatedDepreciation, 2),
                'book_value' => round(max($bookValue, $salvageValue), 2),
            ];

            if ($bookValue <= $salvageValue) {
                break;
            }
        }

        return $schedule;
    }
}
