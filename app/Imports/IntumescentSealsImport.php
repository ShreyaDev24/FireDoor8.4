<?php

namespace App\Imports;

use App\Models\SettingIntumescentSeals2;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class IntumescentSealsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 🔥 Mapping array
        $configurableMap = [
            'strebord'    => 1,
            'halspan'   => 2,
            'flamebreak' => 7,
            'stredor'   => 8,
            'vicaima'   => 4,
        ];

        // Convert Excel value → lowercase (important)
        $configurableName = strtolower(trim($row['configurable_item'] ?? ''));
         if(!isset($configurableMap[$configurableName]) || $configurableMap[$configurableName] == 4) {
             // Log or handle unknown configurable item
                return new SettingIntumescentSeals2([
                    'configurableitems' => $configurableMap[$configurableName] ?? null,
                    'firerating'        => $row['firedoor'] ?? null,
                    'tag'               => $row['firedoor'] ?? null,
                    'configuration'     => $row['configuration'] ?? null,
                    'intumescentSeals'  => $row['intumescent_seal'] ?? null,
                    'brand'             => $row['brand'] ?? null,
                    'firetested'        => $row['fire_tested'] ?? null,
                    'Point1height'      => $row['height1'] ?? null,
                    'Point1width'       => $row['width1'] ?? null,
                    'Point2height'      => $row['height2'] ?? null,
                    'Point2width'       => $row['width2'] ?? null,
                    'FireOnly'          => $this->formatFireType($row['fireonly_type'] ?? null),
                    'editBy'            => Auth::user()->id ?? null,
                ]);
         } else{
                return new SettingIntumescentSeals2([
                'configurableitems' => $configurableMap[$configurableName] ?? null,
                'firerating'        => $row['firedoor'] ?? null,
                'tag'               => $row['firedoor'] ?? null,
                'configuration'     => $row['configuration'] ?? null,
                'intumescentSeals'  => $row['intumescent_seal'] ?? null,
                'brand'             => $row['brand'] ?? null,
                'firetested'        => $row['fire_tested'] ?? null,
                'Point1height'      => $row['height1'] ?? null,
                'Point1width'       => $row['width1'] ?? null,
                'Point2height'      => $row['height2'] ?? null,
                'Point2width'       => $row['width2'] ?? null,
                'FireOnly'          => $this->formatFireType($row['fireonly_type'] ?? null),
                'customeleafTypes'  => $this->mapLeafTypes($row['leaf_type'] ?? null, $row['configurable_item'] ?? null),
                'frameTypes'        => $this->extractNumbers($row['frame'] ?? null),
                'editBy'            => Auth::user()->id ?? null,
            ]);
         }
    }

    private function extractNumbers($value)
    {
        if (!$value) return null;
        // Extract all numbers from string
        preg_match_all('/\d+/', $value, $matches);
        if (empty($matches[0])) return null;
        // Convert to comma separated string
        return implode(',', $matches[0]);
    }

    private function mapLeafTypes($value, $configurable)
    {
        if (!$value) return null;

        // Extract numbers (1,2,3)
        preg_match_all('/\d+/', $value, $matches);

        if (empty($matches[0])) return null;

        $numbers = $matches[0];

        // 🔥 Mapping based on configurable item
        $configurable = strtolower(trim($configurable));

        $map = [];

        if ($configurable === 'halspan') {
            $map = [
                '1' => 7,
                '2' => 8,
                '3' => 9,
            ];
        }
        if ($configurable === 'strebord') {
            $map = [
                '1' => 1,
                '2' => 2,
                '3A' => 3,
                '3B' => 4,
                '4A' => 5,
                '4B' => 6,
            ];
        }

        // Convert numbers → mapped values
        $result = [];

        foreach ($numbers as $num) {
            if (isset($map[$num])) {
                $result[] = $map[$num];
            }
        }

        return implode(',', $result);
    }

    private function formatFireType($value)
    {
        if (!$value) return null;
        $map = [
            'fire and smoke' => 'Fire_and_Smoke',
            'fire smoke and acoustic' => 'Fire_Smoke_and_Acoustic',
            'fire only' => 'Fire_only',
        ];
        $key = strtolower(trim($value));
        return $map[$key] ?? null;
    }
}
