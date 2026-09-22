<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ConsultationType;

class ConsultationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Consultation générale', 'duration_minutes' => 30, 'description' => 'Consultation standard pour un suivi ou un nouveausymptôme.'],
            ['name' => 'Consultation de suivi', 'duration_minutes' => 20, 'description' => 'Suivi d\'un traitement ou d\'une pathologie déjà diagnostiquée'],
            ['name' => 'Urgence', 'duration_minutes' => 15, 'description' => 'Consultation rapide pour un besoin urgen.'],
            ['name' => 'Bilan complet', 'duration_minutes' => 60, 'description' => 'Bilan de santé approfondi.'],
        ];

        foreach ($types as $type) {
            ConsultationType::firstOrCreate(
                ['name' => $type['name']],
                [
                    'duration_minutes' => $type['duration_minutes'],
                    'description' => $type['description'],
                ]
            );
        }
    }
}
