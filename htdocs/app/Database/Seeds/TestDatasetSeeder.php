<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDatasetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Désactiver les contraintes de clés étrangères pour vider proprement
        $this->db->disableForeignKeyChecks();
        $this->db->table('workout_sets')->truncate();
        $this->db->table('strength_workouts')->truncate();
        $this->db->table('exercises')->truncate();
        $this->db->table('coros_activities')->truncate();
        $this->db->enableForeignKeyChecks();

        // ==========================================
        // JEU D'ESSAI : ACTIVITÉS COROS (Cardio)
        // ==========================================
        $activities = [
            // Juillet 2026
            ['coros_activity_id' => 'COROS_001', 'name' => 'Footing matinal', 'sport' => 'Run', 'started_at' => '2026-07-10 07:00:00', 'duration_seconds' => 3600, 'distance_meters' => 10500, 'avg_heart_rate' => 145, 'elevation_gain_m' => 120],
            ['coros_activity_id' => 'COROS_002', 'name' => 'Sortie longue', 'sport' => 'Bike', 'started_at' => '2026-07-25 09:00:00', 'duration_seconds' => 7200, 'distance_meters' => 55000, 'avg_heart_rate' => 135, 'elevation_gain_m' => 450],
            
            // Août 2026
            ['coros_activity_id' => 'COROS_003', 'name' => 'Natation en mer', 'sport' => 'Open Water', 'started_at' => '2026-08-05 10:00:00', 'duration_seconds' => 2400, 'distance_meters' => 1500, 'avg_heart_rate' => 130, 'elevation_gain_m' => 0],
            ['coros_activity_id' => 'COROS_004', 'name' => 'Fractionné Piste', 'sport' => 'Track Run', 'started_at' => '2026-08-18 18:30:00', 'duration_seconds' => 2700, 'distance_meters' => 8000, 'avg_heart_rate' => 165, 'elevation_gain_m' => 10],
            
            // Septembre 2026
            ['coros_activity_id' => 'COROS_005', 'name' => 'Sortie de récupération', 'sport' => 'Run', 'started_at' => '2026-09-02 18:00:00', 'duration_seconds' => 2700, 'distance_meters' => 7000, 'avg_heart_rate' => 138, 'elevation_gain_m' => 50],
            ['coros_activity_id' => 'COROS_006', 'name' => 'Trail en forêt', 'sport' => 'Trail Run', 'started_at' => '2026-09-20 08:30:00', 'duration_seconds' => 5400, 'distance_meters' => 15200, 'avg_heart_rate' => 155, 'elevation_gain_m' => 600],
            
            // Octobre 2026
            ['coros_activity_id' => 'COROS_007', 'name' => 'Vélotaf Aller', 'sport' => 'Bike', 'started_at' => '2026-10-05 07:45:00', 'duration_seconds' => 1500, 'distance_meters' => 12000, 'avg_heart_rate' => 125, 'elevation_gain_m' => 100],
            ['coros_activity_id' => 'COROS_008', 'name' => 'Footing tempo', 'sport' => 'Run', 'started_at' => '2026-10-12 18:15:00', 'duration_seconds' => 3100, 'distance_meters' => 10000, 'avg_heart_rate' => 152, 'elevation_gain_m' => 85],
        ];
        $this->db->table('coros_activities')->insertBatch($activities);

        // ==========================================
        // JEU D'ESSAI : MUSCULATION
        // ==========================================
        
        // 1. Catalogue des exercices
        $exercises = [
            ['id' => 1, 'name' => 'Squat (Barre libre)', 'muscle_group' => 'Jambes'],
            ['id' => 2, 'name' => 'Développé Couché', 'muscle_group' => 'Pectoraux'],
            ['id' => 3, 'name' => 'Soulevé de terre (Deadlift)', 'muscle_group' => 'Jambes/Dos'],
            ['id' => 4, 'name' => 'Tractions (Poids du corps)', 'muscle_group' => 'Dos'],
            ['id' => 5, 'name' => 'Développé Militaire', 'muscle_group' => 'Épaules'],
        ];
        $this->db->table('exercises')->insertBatch($exercises);

        // 2. Historique des séances
        $workouts = [
            ['id' => 1, 'date' => '2026-09-01 18:00:00', 'notes' => 'Séance Reprise - Focus technique'],
            ['id' => 2, 'date' => '2026-09-08 18:30:00', 'notes' => 'Push Day - Bonnes sensations sur le développé couché'],
            ['id' => 3, 'date' => '2026-09-15 18:00:00', 'notes' => 'Leg Day - Lourd sur le Squat'],
            ['id' => 4, 'date' => '2026-09-22 17:45:00', 'notes' => 'Pull Day - Focus Dos et Tractions'],
            ['id' => 5, 'date' => '2026-10-06 18:15:00', 'notes' => 'Full Body - Nouveaux max sur plusieurs exos !'],
        ];
        $this->db->table('strength_workouts')->insertBatch($workouts);

        // 3. Les séries (Sets) avec une progression réaliste
        $sets = [
            // Séance 1 (Reprise)
            ['workout_id' => 1, 'exercise_id' => 1, 'set_number' => 1, 'reps' => 10, 'weight' => 60], // Squat
            ['workout_id' => 1, 'exercise_id' => 1, 'set_number' => 2, 'reps' => 10, 'weight' => 60],
            ['workout_id' => 1, 'exercise_id' => 2, 'set_number' => 1, 'reps' => 10, 'weight' => 50], // Dév Couché
            ['workout_id' => 1, 'exercise_id' => 2, 'set_number' => 2, 'reps' => 10, 'weight' => 50],

            // Séance 2 (Push)
            ['workout_id' => 2, 'exercise_id' => 2, 'set_number' => 1, 'reps' => 8, 'weight' => 55], // Dév Couché monte à 55kg
            ['workout_id' => 2, 'exercise_id' => 2, 'set_number' => 2, 'reps' => 8, 'weight' => 55],
            ['workout_id' => 2, 'exercise_id' => 2, 'set_number' => 3, 'reps' => 6, 'weight' => 60],
            ['workout_id' => 2, 'exercise_id' => 5, 'set_number' => 1, 'reps' => 10, 'weight' => 30], // Dév Militaire
            ['workout_id' => 2, 'exercise_id' => 5, 'set_number' => 2, 'reps' => 10, 'weight' => 30],

            // Séance 3 (Legs)
            ['workout_id' => 3, 'exercise_id' => 1, 'set_number' => 1, 'reps' => 8, 'weight' => 70], // Squat monte à 70kg
            ['workout_id' => 3, 'exercise_id' => 1, 'set_number' => 2, 'reps' => 8, 'weight' => 70],
            ['workout_id' => 3, 'exercise_id' => 1, 'set_number' => 3, 'reps' => 5, 'weight' => 75], // Charge Max Squat
            ['workout_id' => 3, 'exercise_id' => 3, 'set_number' => 1, 'reps' => 5, 'weight' => 90], // Deadlift
            ['workout_id' => 3, 'exercise_id' => 3, 'set_number' => 2, 'reps' => 5, 'weight' => 90],

            // Séance 4 (Pull)
            ['workout_id' => 4, 'exercise_id' => 4, 'set_number' => 1, 'reps' => 8, 'weight' => 0], // Tractions PDC
            ['workout_id' => 4, 'exercise_id' => 4, 'set_number' => 2, 'reps' => 7, 'weight' => 0],
            ['workout_id' => 4, 'exercise_id' => 4, 'set_number' => 3, 'reps' => 6, 'weight' => 0],
            ['workout_id' => 4, 'exercise_id' => 3, 'set_number' => 1, 'reps' => 5, 'weight' => 95], // Deadlift monte à 95kg
            ['workout_id' => 4, 'exercise_id' => 3, 'set_number' => 2, 'reps' => 3, 'weight' => 100],

            // Séance 5 (Full Body - RP)
            ['workout_id' => 5, 'exercise_id' => 1, 'set_number' => 1, 'reps' => 5, 'weight' => 80], // Nouveau Max Squat
            ['workout_id' => 5, 'exercise_id' => 1, 'set_number' => 2, 'reps' => 5, 'weight' => 80],
            ['workout_id' => 5, 'exercise_id' => 2, 'set_number' => 1, 'reps' => 5, 'weight' => 65], // Nouveau Max Dév Couché
            ['workout_id' => 5, 'exercise_id' => 2, 'set_number' => 2, 'reps' => 4, 'weight' => 67.5],
            ['workout_id' => 5, 'exercise_id' => 4, 'set_number' => 1, 'reps' => 5, 'weight' => 10], // Tractions lestées +10kg
            ['workout_id' => 5, 'exercise_id' => 4, 'set_number' => 2, 'reps' => 4, 'weight' => 10],
        ];
        $this->db->table('workout_sets')->insertBatch($sets);
    }
}