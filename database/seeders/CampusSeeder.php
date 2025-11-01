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
            ['Campus_Name' => 'Cauayan'],
            ['Campus_Name' => 'Cabagan'],
            ['Campus_Name' => 'Ilagan'],
            ['Campus_Name' => 'Angadanan'],
            ['Campus_Name' => 'Roxas'],
            ['Campus_Name' => 'Jones'],
            ['Campus_Name' => 'Palanan'],
            ['Campus_Name' => 'San Mateo'],
            ['Campus_Name' => 'San Mariano'],
        ];

        foreach ($campuses as $campus) {
            Campus::firstOrCreate(
                ['Campus_Name' => $campus['Campus_Name']],
                $campus
            );
        }
    }
}