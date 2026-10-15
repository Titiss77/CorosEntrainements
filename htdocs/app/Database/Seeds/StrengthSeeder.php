<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StrengthSeeder extends Seeder
{
    public function run() : void
    {
        // Désactiver temporairement la vérification des clés étrangères pour vider les tables proprement
        $this->db->disableForeignKeyChecks();
        $this->db->table('workout_sets')->truncate();
        $this->db->table('strength_workouts')->truncate();
        $this->db->table('exercises')->truncate();
        $this->db->enableForeignKeyChecks();

        // 1. Catalogue des exercices
        $exercises = [
            ['id' => 1, 'name' => 'Squat (Barre libre)', 'muscle_group' => 'Jambes'],
            ['id' => 2, 'name' => 'Développé Couché', 'muscle_group' => 'Pectoraux'],
            ['id' => 3, 'name' => 'Soulevé de terre (Deadlift)', 'muscle_group' => 'Jambes/Dos'],
            ['id' => 4, 'name' => 'Tirage poitrine', 'muscle_group' => 'Dos'],
            ['id' => 5, 'name' => 'Gainage lesté', 'muscle_group' => 'Tronc'],
        ];
        $this->db->table('exercises')->insertBatch($exercises);

        // 2. Historique des séances
        $workouts = [
            ['id' => 1, 'date' => '2026-09-15 18:00:00', 'notes' => 'Reprise force - Focus puissance jambes pour la nage'],
            ['id' => 2, 'date' => '2026-09-22 18:00:00', 'notes' => 'Séance esthétique haut du corps et renforcement tronc'],
            ['id' => 3, 'date' => '2026-09-29 18:00:00', 'notes' => 'Full body - Bonnes sensations en squat'],
            ['id' => 4, 'date' => '2026-10-06 18:30:00', 'notes' => 'Progression sur le haut du corps'],
        ];
        $this->db->table('strength_workouts')->insertBatch($workouts);

        // 3. Les séries (Reps x Poids)
        $sets = [
            // Séance 1 : Focus Jambes (Squat à 70-75kg)
            ['workout_id' => 1, 'exercise_id' => 1, 'set_number' => 1, 'reps' => 8, 'weight' => 70],
            ['workout_id' => 1, 'exercise_id' => 1, 'set_number' => 2, 'reps' => 8, 'weight' => 70],
            ['workout_id' => 1, 'exercise_id' => 1, 'set_number' => 3, 'reps' => 8, 'weight' => 75],

            // Séance 2 : Haut du corps (Développé Couché 60kg) + Tronc (Gainage lesté 15kg)
            ['workout_id' => 2, 'exercise_id' => 2, 'set_number' => 1, 'reps' => 10, 'weight' => 60],
            ['workout_id' => 2, 'exercise_id' => 2, 'set_number' => 2, 'reps' => 10, 'weight' => 60],
            ['workout_id' => 2, 'exercise_id' => 5, 'set_number' => 1, 'reps' => 1, 'weight' => 15],

            // Séance 3 : Full body (Évolution au Squat : passage à 85kg max)
            ['workout_id' => 3, 'exercise_id' => 1, 'set_number' => 1, 'reps' => 6, 'weight' => 75],
            ['workout_id' => 3, 'exercise_id' => 1, 'set_number' => 2, 'reps' => 6, 'weight' => 80],
            ['workout_id' => 3, 'exercise_id' => 1, 'set_number' => 3, 'reps' => 5, 'weight' => 85],
            
            // Séance 4 : Évolution au Développé Couché (passage à 70kg)
            ['workout_id' => 4, 'exercise_id' => 2, 'set_number' => 1, 'reps' => 8, 'weight' => 65],
            ['workout_id' => 4, 'exercise_id' => 2, 'set_number' => 2, 'reps' => 8, 'weight' => 65],
            ['workout_id' => 4, 'exercise_id' => 2, 'set_number' => 3, 'reps' => 6, 'weight' => 70],
        ];
        $this->db->table('workout_sets')->insertBatch($sets);
    }
}