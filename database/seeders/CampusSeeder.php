<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campus;

class CampusSeeder extends Seeder
{
    public function run()
    {
        $campuses = [
            ['Campus_Name' => 'Echague'],
            ['Campus_Name' => 'Santiago'],
      
        ];

        foreach ($campuses as $campus) {
            Campus::firstOrCreate(
                ['Campus_Name' => $campus['Campus_Name']],
                $campus
            );
        }
    }
}